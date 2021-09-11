<?php

use App\Application\Command\GenerateIngredientsList;
use Symfony\Component\Config\FileLocator;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;

require dirname(__DIR__).'/vendor/autoload.php';

$containerBuilder = new ContainerBuilder();
$loader = new YamlFileLoader($containerBuilder, new FileLocator(__DIR__.'/../config'));
$loader->load('services.yaml');
$containerBuilder->compile();

$generateIngredientsList = $containerBuilder->get(GenerateIngredientsList::class);
$generateIngredientsList->execute();