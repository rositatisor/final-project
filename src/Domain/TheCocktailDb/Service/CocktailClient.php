<?php

declare(strict_types=1);

namespace App\Domain\TheCocktailDb\Service;

use App\Application\IngredientCollection\IngredientCollectionDenormalizer;
use App\Application\IngredientCollection\IngredientSerializer;
use App\Domain\ClientInterface;
use App\Domain\Dto\IngredientCollection;
use App\Exceptions\RequestFailedException;
use Symfony\Component\HttpClient\Exception\ClientException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class CocktailClient implements ClientInterface
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

    public function getIngredients(): IngredientCollection
    {
        $drink = $this->denormalizer->denormalize(
            $this->getCocktail(),
            IngredientCollection::class,
            'array',
        );

        return $drink;
    }

    public function getResponse(): ResponseInterface
    {
        try {
            return $this->http->request(
                self::GET,
                $this->baseUri . self::GET_RANDOM_MEAL_ENDPOINT
            );
        } catch (ClientException $e) {
            throw new RequestFailedException($e->getMessage());
        }
    }

    private function getCocktail(): array
    {
        $content = $this->getResponse()->getContent();
        $drinks = $this->serializer->decode($content, 'json');

        return $drinks['drinks'][self::RANDOM];
    }
}