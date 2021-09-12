<?php

declare(strict_types=1);

namespace App\Domain\Ingredients\UseCase;

use App\Domain\Ingredients\Dto\Ingredient;
use App\Domain\Ingredients\Dto\IngredientListCollection;
use App\Domain\TheMealDb\Service\Client as Meal;
use App\Domain\TheCocktailDb\Service\Client as Cocktail;

class MealAndCocktailIngredientsList
{
    private Meal $meal;
    private Cocktail $cocktail;

    public function __construct(Meal $meal, Cocktail $cocktail)
    {
        $this->meal = $meal;
        $this->cocktail = $cocktail;
    }

    public function execute(): IngredientListCollection
    {
        $listOfIngredients = new IngredientListCollection();

        $mealIngredients = $this->meal->getMeal(); // getIngredients()
        foreach ($mealIngredients->ingredients as $ingredient) {
            assert($ingredient instanceof Ingredient);
            $listOfIngredients->add($ingredient);
        }

        $cocktailIngredients = $this->cocktail->getCocktail(); // getIngredients()
        foreach ($cocktailIngredients->ingredients as $ingredient) {
            assert($ingredient instanceof Ingredient);
            $listOfIngredients->add($ingredient);
        }

         return $listOfIngredients;
    }
}