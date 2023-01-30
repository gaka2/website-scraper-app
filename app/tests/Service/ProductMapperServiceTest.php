<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Dto\ProductOptionDto;

use App\Model\ProductOptionModel;
use App\Parser\MoneyParser;
use App\Service\ProductMapperService;
use Money\Money;
use PHPUnit\Framework\TestCase;
use App\Factory\MoneyParserFactory;

/**
 * @covers \App\Service\ProductMapperService
 *
 * @author Karol Gancarczyk
 *
 */
class ProductMapperServiceTest extends TestCase
{
    protected ProductMapperService $productMapperService;

    protected function setUp(): void
    {
        $innerMoneyParser = (new MoneyParserFactory())->getParser();
        $moneyParser = new MoneyParser($innerMoneyParser);

        $this->productMapperService = new ProductMapperService($moneyParser);
    }

    public function testCreateModelFromDtoYearOption(): void
    {
        $title = 'Basic';
        $description = 'Up to 6GB of data';
        $discount = 'Save £5.86 on the monthly price';

        $productOptionDto = new ProductOptionDto(
            $title,
            $description,
            '£66.00',
            '(inc. VAT) Per Year',
            $discount,
        );

        $expectedModel = new ProductOptionModel(
            $title,
            $description,
            Money::GBP(6600),
            $discount
        );

        $actualModel = $this->productMapperService->createModelFromDto($productOptionDto);

        $this->assertEquals($expectedModel, $actualModel);
    }

    public function testCreateModelFromDtoMonthOption(): void
    {
        $title = 'Basic';
        $description = 'Up to 6GB of data';
        $discount = '';

        $productOptionDto = new ProductOptionDto(
            $title,
            $description,
            '£11.00',
            '(inc. VAT) Per Month',
            $discount,
        );

        $expectedModel = new ProductOptionModel(
            $title,
            $description,
            Money::GBP(12*1100),
            $discount
        );

        $actualModel = $this->productMapperService->createModelFromDto($productOptionDto);

        $this->assertEquals($expectedModel, $actualModel);
    }
}
