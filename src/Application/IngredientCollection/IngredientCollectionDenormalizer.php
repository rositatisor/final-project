<?php

declare(strict_types=1);

namespace App\Application\IngredientCollection;

use App\Domain\Dto\IngredientCollection;
use App\Domain\Ingredients\Dto\Ingredient;
use Symfony\Component\Serializer\Normalizer\DenormalizerInterface;

class IngredientCollectionDenormalizer implements DenormalizerInterface
{
    private const MEASUREMENT = '/[a-zA-Z]+?(?=\s*?[^\w]*?$)/';
    private const QUANTITY = '/(?:\d\d* |)(?:\d\d*|0)(?:\/\d\d*)?/';
    private IngredientDenormalizer $ingredientDenormalizer;

    public function __construct(IngredientDenormalizer $ingredientDenormalizer)
    {
        $this->ingredientDenormalizer = $ingredientDenormalizer;
    }

    public function denormalize($data, string $type, string $format = null, array $context = []): IngredientCollection
    {
        $ingredients = [];
        $ingredientData = $this->getListOfIngredientData($data);
        foreach ($this->match($ingredientData) as $ingredientArray) {
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

    private function getListOfIngredientData(array $data): array
    {
        $ingredientData = [];

        foreach ($data as $key => $value) {
            if ($value === null || $value === '') {
                continue;
            }
            if (str_starts_with($key, 'strIngredient')) {
                $ingredientData['name'][] = $value;
            }
            if (str_starts_with($key, 'strMeasure')) {
                preg_match_all(self::MEASUREMENT, (string)$value, $matchesM);
                $ingredientData['measurement'][] = $matchesM[0][0] ?? '';

                preg_match_all(self::QUANTITY, (string)$value, $matchesQ);
                $ingredientData['quantity'][] = $matchesQ[0][0] ?? '';
            }
        }

        return $ingredientData;
    }

    private function match(array $ingredientData): array
    {
        $listOfIngredients = [];

        for ($i = 0, $iMax = count($ingredientData['name']); $i < $iMax; $i++) {
            $listOfIngredients[] = [
                'quantity' => $ingredientData['quantity'][$i] ?? '',
                'measurement' => $ingredientData['measurement'][$i] ?? '',
                'name' => $ingredientData['name'][$i] ?? '',
            ];
        }

        return $listOfIngredients;
    }
}