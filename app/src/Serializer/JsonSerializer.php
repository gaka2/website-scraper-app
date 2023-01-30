<?php

declare(strict_types=1);

namespace App\Serializer;

use App\Model\ModelInterface;
use Symfony\Component\Serializer\SerializerInterface as SymfonySerializerInterface;

/**
 * Concrete implementation: JSON Serializer
 * It is an adapter for Symfony JSON serializer
 *
 * @author Karol Gancarczyk
 *
 */
class JsonSerializer implements SerializerInterface
{
    public function __construct(
        protected readonly SymfonySerializerInterface $symfonySerializer,
    ) {
    }

    public function serialize(ModelInterface $model): string
    {
        return $this->symfonySerializer->serialize($model, 'json');
    }
}
