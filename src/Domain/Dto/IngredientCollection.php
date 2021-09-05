<?php

namespace App\Domain\Dto;

use App\Domain\Ingredients\Dto\Ingredient;

class IngredientCollection
{
    private array $ingredients;

    public function __construct(Ingredient... $ingredients)
    {
        $this->ingredients = $ingredients;
    }
}