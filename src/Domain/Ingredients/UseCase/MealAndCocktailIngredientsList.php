<?php

declare(strict_types=1);

namespace App\Domain\Ingredients\UseCase;

use App\Domain\Ingredients\Dto\Ingredient;
use App\Domain\Ingredients\Dto\IngredientListCollection;
use App\Domain\TheMealDb\Service\Client as Meal;
use App\Domain\TheCocktailDb\Service\Client as Cocktail;

class MealAndCocktailIngredientsList
{
    // maybe both clients should be separated in different classes?

    private Meal $meal;
    private Cocktail $cocktail;

    public function __construct()
    {
        $this->meal = new Meal();
        $this->cocktail = new Cocktail();
    }

    public function execute(): IngredientListCollection
    {
        $listOfIngredients = new IngredientListCollection();

        $mealIngredients = $this->meal->getIngredients();
        foreach ($mealIngredients as $ingredient) {
            assert($ingredient instanceof Ingredient);
            $listOfIngredients->add($ingredient);
        }

        $cocktailIngredients = $this->cocktail->getIngredients();
        foreach ($cocktailIngredients as $ingredient) {
            assert($ingredient instanceof Ingredient);
            $listOfIngredients->add($ingredient);
        }

         return $listOfIngredients;
    }
}