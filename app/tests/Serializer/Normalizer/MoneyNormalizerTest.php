<?php

declare(strict_types=1);

namespace App\Tests\Serializer\Normalizer;

use App\Factory\MoneyFormatterFactory;
use App\Serializer\Normalizer\MoneyNormalizer;
use Money\Money;
use PHPUnit\Framework\TestCase;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class MoneyNormalizerTest extends TestCase
{
    protected MoneyNormalizer $moneyNormalizer;

    protected function setUp(): void
    {
        $moneyFormatter = (new MoneyFormatterFactory())->getFormatter();

        $this->moneyNormalizer = new MoneyNormalizer($moneyFormatter);
    }

    /**
     * @dataProvider provideDataAndExpectedResult
     */
    public function testSupportsNormalization(mixed $data, bool $expectedSupportsNormalization): void
    {
        $actualSupportsNormalization = $this->moneyNormalizer->supportsNormalization($data);

        $this->assertEquals($expectedSupportsNormalization, $actualSupportsNormalization);
    }

    public function testNormalize(): void
    {
        $money = Money::GBP(6600);
        $expectedNormalizedValue = '£66.00';

        $actualNormalizedValue = $this->moneyNormalizer->normalize($money);

        $this->assertEquals($expectedNormalizedValue, $actualNormalizedValue);
    }

    public function provideDataAndExpectedResult(): array
    {
        return [
            [Money::GBP(6600), true],
            [[], false],
            ['text', false],
            ['£66.00', false],
            [66, false],
            [66.0, false],
            [null, false],
            [true, false],
            [false, false],
        ];
    }
}
