<?php

declare(strict_types=1);

namespace App\Domain\TheMealDb\Service;

use App\Application\IngredientCollection\IngredientCollectionDenormalizer;
use App\Application\IngredientCollection\IngredientSerializer;
use App\Domain\ClientInterface;
use App\Domain\Dto\IngredientCollection;
use App\Exceptions\RequestFailedException;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Symfony\Contracts\HttpClient\ResponseInterface;

class Client implements ClientInterface
{
    private const GET_RANDOM_MEAL_ENDPOINT = '/1/random.php';
    private const GET = 'GET';
    private const RANDOM = 0;

    private HttpClientInterface $http;
    private IngredientSerializer $serializer;
    private IngredientCollectionDenormalizer $denormalizer;
    private string $baseUri;

    public function __construct(
        IngredientSerializer $serializer,
        IngredientCollectionDenormalizer $denormalizer,
        string $baseUri
    ){
        $this->http = HttpClient::create();
        $this->serializer = $serializer;
        $this->denormalizer = $denormalizer;
        $this->baseUri = $baseUri;
    }

    public function getIngredients(): IngredientCollection
    {
        $meal = $this->denormalizer->denormalize(
            $this->getMeal(),
            IngredientCollection::class,
            'array',
        );

        return $meal;
    }

    public function getResponse(): ResponseInterface
    {
        try {
            return $this->http->request(
                self::GET,
                $this->baseUri . self::GET_RANDOM_MEAL_ENDPOINT
            );
        } catch (\Exception $e) {
            throw new RequestFailedException($e->getMessage());
        }
    }

    private function getMeal(): array
    {
        $content = $this->getResponse()->getContent();
        $meals = $this->serializer->decode($content, 'json');

        return $meals['meals'][self::RANDOM];
    }
}