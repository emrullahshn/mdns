<?php


namespace App\Service\SalePriceCalculator;

use App\Entity\Campaign;
use App\Entity\Product;
use App\Library\DecimalMoney;
use App\Library\SalePriceCalculator\Interfaces\SalePriceCalculatorInterface;
use App\Service\CampaignChecker\CampaignCheckerService;
use Doctrine\ORM\EntityManagerInterface;

class SalePriceCalculatorService implements SalePriceCalculatorInterface
{
    /**
     * @var EntityManagerInterface $entityManager
     */
    private $entityManager;

    /**
     * @var CampaignCheckerService $campaignCheckerService
     */
    private $campaignCheckerService;

    /**
     * SalePriceCalculatorService constructor.
     * @param EntityManagerInterface $entityManager
     * @param CampaignCheckerService $campaignCheckerService
     */
    public function __construct(EntityManagerInterface $entityManager, CampaignCheckerService $campaignCheckerService)
    {
        $this->entityManager = $entityManager;
        $this->campaignCheckerService = $campaignCheckerService;
    }

    /**
     * @param array $basket
     * @return array
     */
    public function calculateSalePriceForBasket(array $basket): array
    {
        $items = $basket['items'];
        $preferredCampaign = $this->fetchPreferredCampaign($items);

        if ($preferredCampaign === null) {
            return $basket;
        }

        $campaignRelatedProductIds = $this->getProductIdsRelatedCampaign($preferredCampaign);
        $groupedItems = $this->groupProducts($items, $campaignRelatedProductIds);
        $calculatedItems = $this->applyCampaignAmountToItems($groupedItems, $preferredCampaign);

        return [
            'items' => $calculatedItems,
            'price' => $basket['price'],
            'salePrice' => $this->calculateTotalSalePrice($calculatedItems)
        ];
    }

    /**
     * @param array $items
     * @return Campaign|null
     */
    private function fetchPreferredCampaign(array $items): ?Campaign
    {
        $campaigns = $this->mergeAllCampaigns($items);

        if ($campaigns === null) {
            return null;
        }

        // If have 1 campaign, not need compare by priority
        if (count($campaigns) === 1) {
            return $campaigns[0];
        }

        return $this->compareByPriority($campaigns);
    }

    /**
     * @param array $items
     * @return array
     */
    private function mergeAllCampaigns(array $items): array
    {
        $campaigns = [];

        foreach ($items as $item) {
            $productId = $item['productId'];
            /**
             * @var Product $product
             */
            // Sepette ki ürün sayısının çok yüksek sayılara çıkmayacağını düşündüğüm için burada bir optimizasyon yapmadım
            $product = $this->entityManager->getRepository(Product::class)->find($productId);
            if ($product !== null) {
                $campaigns[$productId] = $product->getRelatedCampaigns()->toArray();
            }
        }

        // Multidimensional array merge and array unique for get unique campaigns
        return array_unique(array_merge_recursive(...$campaigns), SORT_REGULAR);
    }

    /**
     * @param array $campaigns
     * @return Campaign|mixed|null
     */
    private function compareByPriority(array $campaigns)
    {
        $preferredCampaign = null;

        /**
         * @var Campaign $campaign
         */
        foreach ($campaigns as $campaign) {
            if ($this->campaignCheckerService->checkCampaign($campaign) === false) {
                continue;
            }

            if ($preferredCampaign === null) {
                $preferredCampaign = $campaign;
                continue;
            }

            if ($preferredCampaign->getPriority() > $campaign->getPriority()) {
                $preferredCampaign = $campaign;
            }
        }

        return $preferredCampaign;
    }

    /**
     * @param array $groupedItems
     * @param Campaign|null $preferredCampaign
     * @return array
     */
    private function applyCampaignAmountToItems(array $groupedItems, ?Campaign $preferredCampaign): array
    {
        $calculatedItems['includedProducts'] = [];
        $calculatedItems['notIncludedProducts'] = [];

        if (isset($groupedItems['includedItems'])) {
            $calculatedItems['includedProducts'] = $this->calculateIncludedProducts($groupedItems['includedItems'], $preferredCampaign);
        }

        if (isset($groupedItems['notIncludedItems'])) {
            $calculatedItems['notIncludedProducts'] = $this->calculateNotIncludedProducts($groupedItems['notIncludedItems']);
        }

        return array_merge($calculatedItems['includedProducts'], $calculatedItems['notIncludedProducts']);
    }

    /**
     * @param array $calculatedProducts
     * @return float|int
     */
    private function calculateTotalSalePrice(array $calculatedProducts)
    {
        return array_sum(array_column($calculatedProducts, 'salePrice'));
    }

    /**
     * @param array $includedItems
     * @param Campaign $preferredCampaign
     * @return array
     */
    private function calculateIncludedProducts(array $includedItems, Campaign $preferredCampaign): array
    {
        $calculatedItems = [];

        $includedItemsCount = count($includedItems);
        $discountCountForPercentType = $includedItemsCount / 3;

        foreach ($includedItems as $item) {
            $productAmount = DecimalMoney::newMoney($item['price']);
            $campaignAmount = $preferredCampaign->getAmount();
            $campaignType = $preferredCampaign->getType();

            if ($campaignType === Campaign::TYPE_STATIC) {
                $campaignAmount = DecimalMoney::newMoney($preferredCampaign->getAmount());
                $discountAmount = $campaignAmount->divide($includedItemsCount);
                $item['salePrice'] = DecimalMoney::moneyToFloat($productAmount->subtract($discountAmount));
                $calculatedItems[] = $item;
            }

            if ($campaignType === Campaign::TYPE_PERCENT) {
                usort($includedProducts, static function ($a, $b) {
                    return $a['price'] <=> $b['price'];
                });

                if ($discountCountForPercentType >= 1) {
                    $discountAmount = $productAmount->multiply($campaignAmount / 100);
                    $salePrice = $productAmount->subtract($discountAmount);
                    $lastElement = array_pop($includedProducts);
                    $lastElement['salePrice'] = DecimalMoney::moneyToFloat($salePrice);
                    $calculatedItems[] = $lastElement;
                    $discountCountForPercentType--;
                } else {
                    $product['salePrice'] = $item['price'];
                    $calculatedItems[] = $item;
                }

            }
        }

        return $calculatedItems;
    }

    /**
     * @param array $notIncludedProducts
     * @return array
     */
    private function calculateNotIncludedProducts(array $notIncludedProducts): array
    {
        $calculatedProducts = [];

        foreach ($notIncludedProducts as $product) {
            $product['salePrice'] = $product['price'];
            $calculatedProducts[] = $product;
        }

        return $calculatedProducts;
    }

    /**
     * @param Campaign|null $preferredCampaign
     * @return array
     */
    private function getProductIdsRelatedCampaign(?Campaign $preferredCampaign): array
    {
        return array_map(static function (Product $product) {
            return $product->getId();
        }, $preferredCampaign->getRelatedProducts()->toArray());
    }

    /**
     * @param array $items
     * @param array $campaignRelatedProductIds
     * @return array
     */
    private function groupProducts(array $items, array $campaignRelatedProductIds): array
    {
        if ($campaignRelatedProductIds === null) {
            return null;
        }

        $groupedItems = [];

        foreach ($items as $item) {
            if (in_array($item['productId'], $campaignRelatedProductIds, true)) {
                $groupedItems['includedItems'][] = $item;
            } else {
                $groupedItems['notIncludedItems'][] = $item;
            }
        }

        return $groupedItems;
    }
}
