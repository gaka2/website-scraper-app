<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\ProductOptionModel;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class ProductOptionModelTest extends TestCase
{
    public function testCreateProductOptionModel(): void
    {
        $title = 'Basic';
        $description = 'The basic starter subscription';
        $annualPrice = Money::GBP(10800);
        $discount = 'Save £11.90 on the monthly price';

        $productOptionModel = new ProductOptionModel($title, $description, $annualPrice, $discount);

        $this->assertEquals($title, $productOptionModel->getTitle());
        $this->assertEquals($description, $productOptionModel->getDescription());
        $this->assertEquals($annualPrice, $productOptionModel->getAnnualPrice());
        $this->assertEquals($discount, $productOptionModel->getDiscount());
    }
}
