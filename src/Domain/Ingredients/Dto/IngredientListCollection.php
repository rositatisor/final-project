<?php

declare(strict_types=1);

namespace App\Domain\Ingredients\Dto;

class IngredientListCollection
{
    public array $ingredientsList;

    public function add(Ingredient $ingredient): void
    {
        $this->ingredientsList[] = $ingredient;
    }
}