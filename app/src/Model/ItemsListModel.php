<?php

declare(strict_types=1);

namespace App\Model;

use Webmozart\Assert\Assert;

/**
 * Class responsible for representing the list of models
 * It wraps a regular PHP array and ensures that all items have the same type and this is a list (i.e. keys consist of consecutive numbers from 0)
 *
 * @author Karol Gancarczyk
 *
 */
class ItemsListModel implements ModelInterface
{
    protected readonly array $items;

    public function __construct(
        array $items,
    ) {
        Assert::allIsInstanceOf($items, ModelInterface::class);

        //ensure that list is flat (no element is a list itself)
        Assert::allNotInstanceOf($items, self::class);

        Assert::isList($items);

        $this->items = $items;
    }

    public function getItems(): array
    {
        return $this->items;
    }
}
