<?php

namespace App\Library;

use Money\Converter;
use Money\Currencies\ISOCurrencies;
use Money\Currency;
use Money\Exchange\FixedExchange;
use Money\Formatter\DecimalMoneyFormatter;
use Money\Money;
use Money\Parser\DecimalMoneyParser;

class DecimalMoney
{
    /**
     * @var DecimalMoneyParser
     */
    private static $parser;

    /**
     * @var DecimalMoneyFormatter
     */
    private static $formatter;

    /**
     * Ac constructor.
     * @param $parser
     * @param $formatter
     */
    public static function _init($parser = null, $formatter = null): void
    {
        // Init Money Parser and Formatter
        $currencies = new ISOCurrencies();
        if(empty($parser)) {
            $parser = new DecimalMoneyParser($currencies);
        }

        if(empty($formatter)) {
            $formatter = new DecimalMoneyFormatter($currencies);
        }

        self::$parser = $parser;
        self::$formatter = $formatter;
    }

    /**
     * @param float $amount
     * @param string|Currency $currency
     * @return Money
     */
    public static function newMoney(float $amount = 0.00, $currency = 'TRY'): Money
    {
        if (! $currency instanceof Currency) {
            $currency = new Currency($currency);
        }

        return self::getParser()->parse(sprintf('%.2f', $amount), $currency);
    }

    /**
     * @param Money $money
     *
     * @return float
     */
    public static function moneyToFloat(Money $money): float
    {
        return (float) self::getFormatter()->format($money);
    }

    /**
     * @return DecimalMoneyParser
     */
    public static function getParser(): DecimalMoneyParser
    {
        if (self::$parser === null) {
            self::_init();
        }

        return self::$parser;
    }
    
    /**
     * @param DecimalMoneyParser $parser
     * @return void
     */
    public static function setParser($parser): void
    {
        self::$parser = $parser;
    }

    /**
     * @return DecimalMoneyFormatter
     */
    public static function getFormatter(): DecimalMoneyFormatter
    {
        if (self::$formatter === null) {
            self::_init();
        }

        return self::$formatter;
    }

    /**
     * @param DecimalMoneyFormatter $formatter
     */
    public static function setFormatter($formatter): void
    {
        self::$formatter = $formatter;
    }
}
