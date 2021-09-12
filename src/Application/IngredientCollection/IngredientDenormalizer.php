<?php

declare(strict_types=1);

namespace App\Application\IngredientCollection;

use App\Domain\Ingredients\Dto\Ingredient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class IngredientDenormalizer implements DenormalizerInterface
{
    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        return new Ingredient(
            $data['quantity'] ?? '',
            $data['measurement'] ?? '',
            $data['name'] ?? '',
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === Ingredient::class;
    }
}