<?php

declare(strict_types=1);

namespace App\Domain\TheCocktailDb\Service;

use App\Application\IngredientCollection\IngredientCollectionDenormalizer;
use App\Application\IngredientCollection\IngredientDenormalizer;
use App\Domain\Dto\IngredientCollection;
use App\Domain\Ingredients\Dto\Ingredient;
use Symfony\Component\HttpClient\HttpClient;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\AbstractNormalizer;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Contracts\HttpClient\HttpClientInterface;
use Doctrine\Common\Annotations\AnnotationReader;

class Client
{
    private const GET_RANDOM_MEAL_ENDPOINT = '/1/random.php';
    private const GET = 'GET';
    private HttpClientInterface $http;

    public function __construct()
    {
        $this->http = HttpClient::create();
    }

    public function getCocktail()
    {
         $response = $this->http->request(
             self::GET,
             'https://www.thecocktaildb.com/api/json/v1' . self::GET_RANDOM_MEAL_ENDPOINT
         ); // TODO: maybe transfer API request information to yaml

        $content = $response->getContent();

        // TODO: extract further logic to new class
        $classMetadataFactory = new ClassMetaDataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)];
        $denormalizer = new IngredientCollectionDenormalizer(new IngredientDenormalizer());
        $serializer = new Serializer($normalizers, $encoders);

        $drinks = $serializer->decode($content, 'json');
        $cocktail = $drinks['drinks'][0];

        $drink = $denormalizer->denormalize(
            $this->getListOfIngredients($cocktail),
            IngredientCollection::class,
            'array',
        );

        return $drink;
    }

    public function getIngredients(): IngredientCollection
    {
        return new IngredientCollection();
    }

    // TODO: refactor method
    public function getListOfIngredients($cocktail): array
    {
        $ingredient = [];

        foreach ($cocktail as $key => $value) {
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
                'name' => $ingredient['name'][$i],
            ];
        }

        return $listOfIngredients;
    }
}