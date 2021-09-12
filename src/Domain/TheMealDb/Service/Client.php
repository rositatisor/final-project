<?php

declare(strict_types=1);

namespace App\Domain\TheMealDb\Service;

use App\Application\IngredientCollection\IngredientCollectionDenormalizer;
use App\Application\IngredientCollection\IngredientSerializer;
use App\Domain\Dto\IngredientCollection;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class Client
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

    public function getMeal()
    {
        $response = $this->http->request(
            self::GET,
            $this->baseUri . self::GET_RANDOM_MEAL_ENDPOINT
        );

        $content = $response->getContent();

        $meals = $this->serializer->decode($content, 'json');
        $mealRecipe = $meals['meals'][self::RANDOM];
        $meal = $this->denormalizer->denormalize(
            $this->getListOfIngredients($mealRecipe),
            IngredientCollection::class,
            'array',
        );

        return $meal;
    }

    public function getIngredients(): IngredientCollection
    {
        return new IngredientCollection();
    }

    // TODO: refactor method
    public function getListOfIngredients($meal): array
    {
        $ingredient = [];

        foreach ($meal as $key => $value) {
            if ($value === null) {
                continue;
            }
            if (str_starts_with($key, 'strIngredient')) {
                $ingredient['name'][] = $value;
            }
            if (str_starts_with($key, 'strMeasure')) {
                $patternM = '/[a-zA-Z]+?(?=\s*?[^\w]*?$)/';
                preg_match_all($patternM, (string) $value, $matchesM);

                $ingredient['measurement'][] = $matchesM[0][0] ?? '';

                $patternQ = '/(?:\d\d* |)(?:\d\d*|0)(?:\/\d\d*)?/';
                preg_match_all($patternQ, (string) $value, $matchesQ);

                $ingredient['quantity'][] = $matchesQ[0][0] ?? '';
            }
        }

        $listOfIngredients = [];

        for($i = 0, $iMax = count($ingredient); $i < $iMax; $i++) {
            $listOfIngredients[] = [
                'quantity' => $ingredient['quantity'][$i] ?? '',
                'measurement' => $ingredient['measurement'][$i] ?? '',
                'name' => $ingredient['name'][$i] ?? '',
            ];
        }

        return $listOfIngredients;
    }
}