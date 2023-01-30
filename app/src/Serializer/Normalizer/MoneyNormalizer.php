<?php

declare(strict_types=1);

namespace App\Serializer\Normalizer;

use Money\Currencies\ISOCurrencies;
use Money\Formatter\IntlMoneyFormatter;
use Money\Money;
use Money\MoneyFormatter;
use NumberFormatter;
use Symfony\Component\Serializer\Normalizer\NormalizerInterface;

/**
 * Class responsible for defining how Money objects will be normalized
 * Used by Symfony Serializer
 *
 * @author Karol Gancarczyk
 *
 */
class MoneyNormalizer implements NormalizerInterface
{
    public function __construct(
        protected readonly MoneyFormatter $moneyFormatter,
    ) {
    }

    public function normalize(mixed $object, string $format = null, array $context = []): string
    {
        return $this->moneyFormatter->format($object);
    }

    public function supportsNormalization(mixed $data, string $format = null, array $context = []): bool
    {
        return $data instanceof Money;
    }
}
