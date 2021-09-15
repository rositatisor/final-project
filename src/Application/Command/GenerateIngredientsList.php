<?php

declare(strict_types=1);

namespace App\Application\Command;

use App\Domain\Ingredients\Dto\Ingredient;
use App\Domain\Ingredients\UseCase\MealAndCocktailIngredientsFetcher;
use App\UserInterface\Output\ConsoleOutputPrinter;

class GenerateIngredientsList
{
    private MealAndCocktailIngredientsFetcher $useCase;
    private ConsoleOutputPrinter $output;

    public function __construct(MealAndCocktailIngredientsFetcher $ingredientsList, ConsoleOutputPrinter $output)
    {
        $this->useCase = $ingredientsList;
        $this->output = $output;
    }

    public function execute(): void
    {
         $ingredients = $this->useCase->execute();
         foreach($ingredients->ingredientsList as $ingredient) {
             $this->output->print($this->buildOutputString($ingredient));
         }
    }

    private function buildOutputString(Ingredient $ingredient): string
    {
        return sprintf('%s %s %s' . PHP_EOL, $ingredient->getQuantity(), $ingredient->getMeasurement(), $ingredient->getName());
    }
}