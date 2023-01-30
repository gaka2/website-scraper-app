<?php

declare(strict_types=1);

namespace App\Service;

use App\Model\ProductOptionModel;

/**
 *
 * @author Karol Gancarczyk
 *
 */
class DescendingAnnualPriceSorter implements ProductsSorterInterface
{
    public function sort(array $products): array
    {
        $productsSorted = $products; //copy of original array

        usort($productsSorted, function (ProductOptionModel $m1, ProductOptionModel $m2): int {
            return $m2->getAnnualPrice()->compare($m1->getAnnualPrice());
        });

        return $productsSorted;
    }
}
