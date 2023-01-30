<?php

declare(strict_types=1);

namespace App\Tests\Service;

use App\Model\ProductOptionModel;
use App\Service\DescendingAnnualPriceSorter;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class DescendingAnnualPriceSorterTest extends TestCase
{
    protected DescendingAnnualPriceSorter $descendingAnnualPriceSorter;

    protected function setUp(): void
    {
        $this->descendingAnnualPriceSorter = new DescendingAnnualPriceSorter();
    }

    public function testSortEmpty(): void
    {
        $actualSorted = $this->descendingAnnualPriceSorter->sort([]);

        $this->assertEmpty($actualSorted);
    }

    public function testSortNonEmpty(): void
    {
        $productOption_1 = new ProductOptionModel('title', 'description', Money::GBP(6600), 'discount');
        $productOption_2 = new ProductOptionModel('title 2', 'description 2', Money::GBP(12200), 'discount 2');

        $items = [
            $productOption_1,
            $productOption_2,
        ];

        $expectedSorted = [
            $productOption_2,
            $productOption_1,
        ];

        $actualSorted = $this->descendingAnnualPriceSorter->sort($items);

        $this->assertEquals($expectedSorted, $actualSorted);
    }
}
