<?php

declare(strict_types=1);

namespace App\Factory;

use Money\Currencies\ISOCurrencies;
use Money\MoneyParser;
use NumberFormatter;
use Money\Parser\IntlMoneyParser;

/**
 * Class responsible for creation of MoneyParser
 * Used by Symfony DI
 *
 * @author Karol Gancarczyk
 *
 */
class MoneyParserFactory
{
    public function getParser(): MoneyParser
    {
        $currencies = new ISOCurrencies();
        $numberFormatter = new NumberFormatter('en_GB', NumberFormatter::CURRENCY);
        return new IntlMoneyParser($numberFormatter, $currencies);
    }
}
