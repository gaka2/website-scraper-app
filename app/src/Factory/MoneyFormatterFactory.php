<?php

declare(strict_types=1);

namespace App\Factory;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\MoneyFormatter;
use NumberFormatter;

/**
 * Class responsible for creation of MoneyFormatter
 * Used by Symfony DI
 *
 * @author Karol Gancarczyk
 *
 */
class MoneyFormatterFactory
{
    public function getFormatter(): MoneyFormatter
    {
        $currencies = new ISOCurrencies();
        $numberFormatter = new NumberFormatter('en_GB', NumberFormatter::CURRENCY);

        return new IntlMoneyFormatter($numberFormatter, $currencies);
    }
}
