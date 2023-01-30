<?php

declare(strict_types=1);

namespace App\Model;

use Money\Money;

/**
 * Class responsible for defining output item structure
 *
 * @author Karol Gancarczyk
 *
 */
class ProductOptionModel implements ModelInterface
{
    public function __construct(
        protected readonly string $title,
        protected readonly string $description,
        protected readonly Money $annualPrice,
        protected readonly string $discount,
    ) {
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function getDescription(): string
    {
        return $this->description;
    }

    public function getAnnualPrice(): Money
    {
        return $this->annualPrice;
    }

    public function getDiscount(): string
    {
        return $this->discount;
    }
}
