<?php

declare(strict_types=1);

namespace App\Dto;

/**
 * Class represents single package (product option) data extracted from HTML
 *
 * @author Karol Gancarczyk
 *
 */
class ProductOptionDto
{
    public function __construct(
        protected readonly string $title,
        protected readonly string $description,
        protected readonly string $price,
        protected readonly string $priceDescription,
        protected readonly string $discountDescription,
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

    public function getPrice(): string
    {
        return $this->price;
    }

    public function getPriceDescription(): string
    {
        return $this->priceDescription;
    }

    public function getDiscountDescription(): string
    {
        return $this->discountDescription;
    }
}
