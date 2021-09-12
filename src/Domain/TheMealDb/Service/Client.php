<?php

declare(strict_types=1);

namespace App\Domain\TheMealDb\Service;

use App\Domain\Dto\IngredientCollection;

class Client
{
    public function getMeal()
    {
        
    }

    public function getIngredients(): IngredientCollection
    {
        return new IngredientCollection();
    }
}