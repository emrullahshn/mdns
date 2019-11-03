<?php

namespace App\Controller;

use App\Service\SalePriceCalculator\SalePriceCalculatorService;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Annotations;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

/**
 * Class ApiController
 * @package App\Controller
 * @Route(path="/api", name="api_")
 */
class ApiController extends AbstractFOSRestController
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
     * @Annotations\Post("/sale-price-calculate")
     * @param Request $request
     * @param EntityManagerInterface $entityManager
     * @return Response
     */
    public function salePriceCalculate(Request $request, EntityManagerInterface $entityManager): Response
    {
        $content = json_decode($request->getContent(), true);
        $data = [
            'basket' => $this->salePriceCalculatorService->calculateSalePriceForBasket($content['basket'])
        ];
        $view = $this->view($data, 200);
        return $this->handleView($view);
    }
}
