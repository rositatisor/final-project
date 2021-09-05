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
         $ingredients = $this->useCase->execute();
         foreach($ingredients as $ingredient) {
             $this->output->print($this->buildOutputString($ingredient));
         }
    }

    private function buildOutputString(Ingredient $ingredient): string
    {
        return sprintf('%s %s %s', $ingredient->getQuantity(), $ingredient->getMeasurement(), $ingredient->getName());
    }
}