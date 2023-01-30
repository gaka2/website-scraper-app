<?php

declare(strict_types=1);

namespace App\Tests\Model;

use App\Model\ItemsListModel;
use App\Model\ProductOptionModel;
use Money\Money;
use PHPUnit\Framework\TestCase;
use stdClass;
use Webmozart\Assert\InvalidArgumentException;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class ItemsListModelTest extends TestCase
{
    public function testCreateItemListModel(): void
    {
        $items = [
            new ProductOptionModel('title', 'description', Money::GBP(6600), 'discount'),
            new ProductOptionModel('title 2', 'description 2', Money::GBP(12200), 'discount 2'),
        ];

        $itemsListModel = new ItemsListModel($items);

        $this->assertEquals($items, $itemsListModel->getItems());
    }

    public function testCreateEmptyItemListModel(): void
    {
        $itemsListModel = new ItemsListModel([]);

        $this->assertEmpty($itemsListModel->getItems());
    }

    /**
     * @dataProvider provideArrayWithNonModelsItems
     */
    public function testShouldThrowExceptionWhenCreatingListNotOnlyFromModels(array $items): void
    {
        $this->expectException(InvalidArgumentException::class);

        new ItemsListModel($items);
    }

    public function testShouldThrowExceptionWhenCreatingListContainingListModel(): void
    {
        $innerItemsListModel = new ItemsListModel([]);

        $items = [
            new ProductOptionModel('title', 'description', Money::GBP(6600), 'discount'),
            $innerItemsListModel,
        ];

        $this->expectException(InvalidArgumentException::class);

        new ItemsListModel($items);
    }

    public function testShouldThrowExceptionWhenCreatingListFromAssociativeArray(): void
    {
        $items = [
            'a' => new ProductOptionModel('title', 'description', Money::GBP(6600), 'discount'),
            'b' => new ProductOptionModel('title 2', 'description 2', Money::GBP(12200), 'discount 2'),
        ];

        $this->expectException(InvalidArgumentException::class);

        new ItemsListModel($items);
    }

    public function provideArrayWithNonModelsItems(): array
    {
        return [
            [
                [
                    new ProductOptionModel('title', 'description', Money::GBP(6600), 'discount'),
                    'not a model',
                ]
            ],
            [
                [
                    new stdClass(),
                ]
            ],
        ];
    }
}
