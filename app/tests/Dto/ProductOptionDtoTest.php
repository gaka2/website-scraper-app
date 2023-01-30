<?php

declare(strict_types=1);

namespace App\Tests\Dto;

use App\Dto\ProductOptionDto;
use PHPUnit\Framework\TestCase;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class ProductOptionDtoTest extends TestCase
{
    public function testCreateProductOptionDto(): void
    {
        $title = 'Standard: 12GB Data - 1 Year';
        $description = 'Up to 12GB of data per year including 420 SMS (5p / MB data and 4p / SMS thereafter)';
        $price = '£108.00';
        $priceDescription = '(inc. VAT) Per Year';
        $discountDescription = 'Save £11.90 on the monthly price';

        $poductOptionDto = new ProductOptionDto($title, $description, $price, $priceDescription, $discountDescription);

        $this->assertEquals($title, $poductOptionDto->getTitle());
        $this->assertEquals($description, $poductOptionDto->getDescription());
        $this->assertEquals($price, $poductOptionDto->getPrice());
        $this->assertEquals($priceDescription, $poductOptionDto->getPriceDescription());
        $this->assertEquals($discountDescription, $poductOptionDto->getDiscountDescription());
    }
}
