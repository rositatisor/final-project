<?php

declare(strict_types=1);

namespace App\Domain\Ingredients\Dto;

class Ingredient
{
    private int $quantity;
    private string $measurement;
    private string $name;

    public function __construct(int $quantity, string $measurement, string $name)
    {
        $this->quantity = $quantity;
        $this->measurement = $measurement;
        $this->name = $name;
    }

    public function getQuantity(): int
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