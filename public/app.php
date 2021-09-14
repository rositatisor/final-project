<?php

use App\Application\Command\GenerateIngredientsList;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\DependencyInjection\ParameterBag\ParameterBag;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

$dotenv = new Dotenv();
$dotenv->load(__DIR__.'/../.env', __DIR__.'/../.env.local');

$containerBuilder = new ContainerBuilder(new ParameterBag($_ENV));
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__.'/../config'));
$loader->load('services.yaml');
$containerBuilder->compile();

$generateIngredientsList = $containerBuilder->get(GenerateIngredientsList::class);
$generateIngredientsList->execute();