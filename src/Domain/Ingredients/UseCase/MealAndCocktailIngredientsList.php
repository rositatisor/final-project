<?php

declare(strict_types=1);

namespace App\Domain\Ingredients\UseCase;

use App\Domain\Ingredients\Dto\IngredientListCollection;
use App\Domain\Meal\Service\Client as Meal;
use App\Domain\Cocktail\Service\Client as Cocktail;

class MealAndCocktailIngredientsList
{
    private Meal $meal;
    private Cocktail $cocktail;

    public function __construct()
    {
        // mealClient -> MealCollection [Meal]
        $this->meal = new Meal;
        // cocktailClient -> CocktailCollection [Cocktail]
        $this->cocktail = new Cocktail();
        // [Meal] & [Cocktail] -> Ingredient
    }

    public function execute(): IngredientListCollection
    {
        // fetch random meal recipe
        // $mealClient->getMeal(); or getMealIngredients();

        // fetch random cocktail recipe
        // $cocktailClient->getCocktail(); or getCocktailIngredients();

        // return ingredients list collection
        // return IngredientListCollection $ingredientsList;
    }
}