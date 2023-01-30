<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\ProductOptionModel;
use App\Dto\ProductOptionDto;
use App\Parser\MoneyParser;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class ProductMapperService
{
    private const MONTHS_IN_YEAR = 12;

    public function __construct(
        protected readonly MoneyParser $moneyParser,
    ) {
    }

    public function createModelFromDto(ProductOptionDto $dto): ProductOptionModel
    {
        $price = $this->moneyParser->parse($dto->getPrice());

        $annualPrice = match (true) {
            str_contains(mb_strtolower($dto->getPriceDescription()), 'per month') => $price->multiply(self::MONTHS_IN_YEAR),
            str_contains(mb_strtolower($dto->getPriceDescription()), 'per year') => $price,
        };

        return new ProductOptionModel($dto->getTitle(), $dto->getDescription(), $annualPrice, $dto->getDiscountDescription());
    }
}
