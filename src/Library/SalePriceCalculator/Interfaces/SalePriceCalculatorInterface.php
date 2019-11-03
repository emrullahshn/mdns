<?php


namespace App\Library\SalePriceCalculator\Interfaces;


interface SalePriceCalculatorInterface
{
    /**
     * @param array $basket
     * @return array
     */
    public function calculateSalePriceForBasket(array $basket): array;
}
