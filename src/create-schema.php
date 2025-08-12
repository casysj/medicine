<?php
// create-schema.php

use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\EntityManager;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\Tools\SchemaTool;

require_once "vendor/autoload.php";

$config = include 'config/autoload/doctrine.local.php';
$dbParams = $config['doctrine']['connection']['orm_default'];

$isDevMode = true;
$ormConfig = ORMSetup::createAttributeMetadataConfiguration(
    [__DIR__ . '/module/Application/src/Entity'],
    $isDevMode
);

try {
    $connection = DriverManager::getConnection($dbParams['params']);
    $entityManager = new EntityManager($connection, $ormConfig);

    echo "db conected!\n";

    $metadatas = $entityManager->getMetadataFactory()->getAllMetadata();

    if (empty($metadatas)) {
        echo "entity not found\n";
        exit(1);
    }

    echo "found Entity: " . count($metadatas) . "개\n";
    foreach ($metadatas as $metadata) {
        echo "- " . $metadata->getName() . "\n";
    }

    $schemaTool = new SchemaTool($entityManager);

    echo "\creating...\n";
    
    $schemaTool->dropDatabase();
    $schemaTool->createSchema($metadatas);

    echo "created!\n";
    echo "Adminer check.\n";

} catch (Exception $e) {
    echo "error: " . $e->getMessage() . "\n";
    echo "stack: " . $e->getTraceAsString() . "\n";
}