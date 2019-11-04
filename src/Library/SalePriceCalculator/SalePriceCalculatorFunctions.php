<?php
namespace App\Library\SalePriceCalculator;

use App\Library\DecimalMoney;
use Money\Money;

class SalePriceCalculatorFunctions
{
    /**
     * @param array $includedItems
     * @return array
     */
    public static function sortDescItemsByPrice(array $includedItems): array
    {
        usort($includedItems, static function ($a, $b) {
            return $a['price'] <=> $b['price'];
        });

        return array_reverse($includedItems);
    }

    /**
     * @param array $item
     * @param Money $itemPrice
     * @param float $campaignAmount
     * @return array
     */
    public static function applyPercentTypeDiscountToItem(array $item, Money $itemPrice, float $campaignAmount): array
    {
        $discountAmount = $itemPrice->multiply($campaignAmount / 100);
        $salePrice = $itemPrice->subtract($discountAmount);
        $item['salePrice'] = DecimalMoney::moneyToFloat($salePrice);
        return $item;
    }

    /**
     * @param array $item
     * @param Money $campaignAmount
     * @param Money $itemPrice
     * @param int $includedItemsCount
     * @return array
     */
    public static function applyStaticTypeDiscountToItem(array $item, Money $campaignAmount, Money $itemPrice, int $includedItemsCount): array
    {
        $discountAmount = $campaignAmount->divide($includedItemsCount);
        $item['salePrice'] = DecimalMoney::moneyToFloat($itemPrice->subtract($discountAmount));

        return $item;
    }
}
