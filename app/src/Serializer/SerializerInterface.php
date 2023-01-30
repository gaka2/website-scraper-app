<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\ModelInterface;

/**
 * Interface enforces that only objects from 'Model' layer can be serialized
 *
 * @author Karol Gancarczyk
 *
 */
interface SerializerInterface
{
    public function serialize(ModelInterface $model): string;
}
