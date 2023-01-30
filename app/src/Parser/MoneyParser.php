<?php

declare(strict_types=1);

namespace App\Parser;

use App\Exception\ParserException;
use Money\Exception\ParserException as MoneyParserException;
use Money\Money;
use Money\MoneyParser as InnerMoneyParser;

/**
 * Decorates \Money\MoneyParser
 *
 * @author Karol Gancarczyk
 *
 */
class MoneyParser
{
    public function __construct(
        protected readonly InnerMoneyParser $innerMoneyParser,
    ) {
    }

    public function parse(string $text): Money
    {
        try {
            return $this->innerMoneyParser->parse($text);
        } catch (MoneyParserException $e) {
            throw new ParserException($e->getMessage());
        }
    }
}
