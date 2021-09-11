<?php

declare(strict_types=1);

namespace App\Application\IngredientCollection;

use Doctrine\Common\Annotations\AnnotationReader;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Mapping\Factory\ClassMetadataFactory;
use Symfony\Component\Serializer\Mapping\Loader\AnnotationLoader;
use Symfony\Component\Serializer\NameConverter\MetadataAwareNameConverter;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;

class IngredientSerializer extends Serializer
{
    public function __construct()
    {
        $classMetadataFactory = new ClassMetaDataFactory(new AnnotationLoader(new AnnotationReader()));
        $metadataAwareNameConverter = new MetadataAwareNameConverter($classMetadataFactory);
        $encoders = [new JsonEncoder()];
        $normalizers = [new ObjectNormalizer($classMetadataFactory, $metadataAwareNameConverter)];

        parent::__construct($normalizers, $encoders);
    }

}