<?php

declare(strict_types=1);

namespace App\Domain\Ingredients\UseCase;

use App\Domain\Ingredients\Dto\Ingredient;
use App\Domain\Ingredients\Dto\IngredientListCollection;
use App\Domain\TheMealDb\Service\MealClient;
use App\Domain\TheCocktailDb\Service\CocktailClient;

class MealAndCocktailIngredientsFetcher
{
    private MealClient $meal;
    private CocktailClient $cocktail;

    public function __construct(MealClient $meal, CocktailClient $cocktail)
    {
        $this->meal = $meal;
        $this->cocktail = $cocktail;
    }

    public function execute(): IngredientListCollection
    {
        $listOfIngredients = new IngredientListCollection();

        $mealIngredients = $this->meal->getIngredients();
        foreach ($mealIngredients->ingredients as $ingredient) {
            assert($ingredient instanceof Ingredient);
            $listOfIngredients->add($ingredient);
        }

        $cocktailIngredients = $this->cocktail->getIngredients();
        foreach ($cocktailIngredients->ingredients as $ingredient) {
            assert($ingredient instanceof Ingredient);
            $listOfIngredients->add($ingredient);
        }

        return $listOfIngredients;
    }
}