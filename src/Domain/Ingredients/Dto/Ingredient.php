<?php

declare(strict_types=1);

namespace App\Domain\Ingredients\Dto;

class Ingredient
{
    private string $quantity;
    private string $measurement;
    private string $name;

    public function __construct(string $quantity, string $measurement, string $name)
    {
        $this->quantity = $quantity;
        $this->measurement = $measurement;
        $this->name = $name;
    }

    public function getQuantity(): string
    {
        return $this->quantity;
    }

    public function getMeasurement(): string
    {
        return $this->measurement;
    }

    public function getName(): string
    {
        return $this->name;
    }
}