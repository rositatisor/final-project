<?php

namespace App\Domain;

use App\Domain\Dto\IngredientCollection;
use Symfony\Contracts\HttpClient\ResponseInterface;

interface ClientInterface
{
    public function getIngredients(): IngredientCollection;

    public function getResponse(): ResponseInterface;
}