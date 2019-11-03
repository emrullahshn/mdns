<?php

namespace App\Controller;

use App\Service\SalePriceCalculator\SalePriceCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 * @Route(path="/api", name="api_")
 */
class ApiController extends AbstractController
{
    /**
     * @var SalePriceCalculatorService $salePriceCalculatorService
     */
    private $salePriceCalculatorService;

    /**
     * ApiController constructor.
     * @param SalePriceCalculatorService $salePriceCalculatorService
     */
    public function __construct(SalePriceCalculatorService $salePriceCalculatorService)
    {
        $this->salePriceCalculatorService = $salePriceCalculatorService;
    }

    /**
     *
     * @Annotations\Post(path="/sale-price-calculate", name="sale_price_calculate")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return JsonResponse
     */
    public function salePriceCalculate(Request $request, EntityManagerInterface $entityManager): JsonResponse
    {
        $content = json_decode($request->getContent(), true);

        return new JsonResponse(['basket' => $this->salePriceCalculatorService->calculateSalePriceForBasket($content['basket'])]);
    }
}
