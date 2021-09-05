<?php

declare(strict_types=1);

namespace App\Application\IngredientCollection;

use App\Domain\Dto\IngredientCollection;
use App\Domain\Ingredients\Dto\Ingredient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class IngredientCollectionDenormalizer implements DenormalizerInterface
{
    private IngredientDenormalizer $ingredientDenormalizer;

    public function __construct(IngredientDenormalizer $ingredientDenormalizer)
    {
        $this->ingredientDenormalizer = $ingredientDenormalizer;
    }

    public function denormalize($data, string $type, string $format = null, array $context = [])
    {
        $ingredients = [];
        foreach ($data as $ingredientArray) {
            $ingredients[] = $this->ingredientDenormalizer->denormalize($ingredientArray, Ingredient::class);
        }

        return new IngredientCollection(
            ...$ingredients
        );
    }

    public function supportsDenormalization($data, string $type, string $format = null): bool
    {
        return $type === IngredientCollection::class;
    }
}