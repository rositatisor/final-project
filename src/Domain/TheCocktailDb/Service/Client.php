<?php

declare(strict_types=1);

namespace App\Domain\TheCocktailDb\Service;

use App\Application\IngredientCollection\IngredientCollectionDenormalizer;
use App\Application\IngredientCollection\IngredientSerializer;
use App\Domain\Dto\IngredientCollection;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
{
    private const GET_RANDOM_MEAL_ENDPOINT = '/1/random.php';
    private const GET = 'GET';
    private const RANDOM = 0;

    private HttpClientInterface $http;
    private Serializer $serializer;
    private IngredientCollectionDenormalizer $denormalizer;
    private string $baseUri;

    public function __construct(
        IngredientSerializer $serializer,
        IngredientCollectionDenormalizer $denormalizer,
        string $baseUri
    )
    {
        $this->http = HttpClient::create();
        $this->serializer = $serializer;
        $this->denormalizer = $denormalizer;
        $this->baseUri = $baseUri;
    }

    public function getCocktail(): IngredientCollection
    {
         $response = $this->http->request(
             self::GET,
             $this->baseUri . self::GET_RANDOM_MEAL_ENDPOINT
         );

        $content = $response->getContent();

        $drinks = $this->serializer->decode($content, 'json');
        $cocktail = $drinks['drinks'][self::RANDOM];
        $drink = $this->denormalizer->denormalize(
            $cocktail,
            IngredientCollection::class,
            'array',
        );

        return $drink;
    }
}