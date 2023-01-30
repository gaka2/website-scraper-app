<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Exception\ParserException;
use App\Factory\MoneyParserFactory;
use App\Parser\MoneyParser;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class MoneyParserTest extends TestCase
{
    protected MoneyParser $moneyParser;

    protected function setUp(): void
    {
        $innerMoneyParser = (new MoneyParserFactory())->getParser();
        $this->moneyParser = new MoneyParser($innerMoneyParser);
    }

    public function testParse(): void
    {
        $text = '£66.00';

        $expectedMoney = Money::GBP(6600);

        $actualMoney = $this->moneyParser->parse($text);

        $this->assertEquals($expectedMoney, $actualMoney);
    }

    public function testParseShouldThrowException(): void
    {
        $text = '66.00';

        $this->expectException(ParserException::class);

        $this->moneyParser->parse($text);
    }
}
