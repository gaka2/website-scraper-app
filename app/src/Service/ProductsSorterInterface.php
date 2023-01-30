<?php

declare(strict_types=1);

namespace App\Service;

/**
 *
 * @author Karol Gancarczyk
 *
 */
interface ProductsSorterInterface
{
    public function sort(array $products): array;
}
