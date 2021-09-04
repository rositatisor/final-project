<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Ingredients\Dto\Ingredient;
use App\Domain\Ingredients\UseCase\MealAndCocktailIngredientsList;
use App\UserInterface\Output\ConsoleOutputPrinter;

class GenerateIngredientsList
{
    private MealAndCocktailIngredientsList $useCase;
    private ConsoleOutputPrinter $output;

    public function __construct()
    {
        $this->useCase = new MealAndCocktailIngredientsList();
        $this->output = new ConsoleOutputPrinter();
    }

    public function execute():void
    {
        // receive list of ingredients
        // $ingredients = $this->useCase->execute();
        // foreach($ingredients as $ingredient)
        // assert($ingredient instanceof Ingredient);

        // pass to output to print
        // $this->output->print($ingredient);
        // format: %f %s %s -> e.g. 100 g rice
        // $ingredient->getQuantity();
        // $ingredient->getMeasurement();
        // $ingredient->getName();
    }
}