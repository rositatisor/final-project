<?php

namespace App\Tests\Application\Command;

use App\Application\Command\GenerateIngredientsList;
use PHPUnit\Framework\TestCase;

class GenerateIngredientsListTest extends TestCase
{
    public function setUp(): void
    {
        $this->command = new GenerateIngredientsList();
    }
    public function test_it()
    {
        self::assertTrue(true);
    }
}
