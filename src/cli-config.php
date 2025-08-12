<?php
// cli-config.php

use Doctrine\ORM\Tools\Console\ConsoleRunner;
use Doctrine\ORM\ORMSetup;
use Doctrine\ORM\EntityManager;
use Doctrine\DBAL\DriverManager;
use Doctrine\ORM\Tools\Console\EntityManagerProvider\SingleManagerProvider;

require_once 'vendor/autoload.php';

$config = include 'config/autoload/doctrine.local.php';

$dbParams = $config['doctrine']['connection']['orm_default'];

$config = ORMSetup::createAttributeMetadataConfiguration(
    paths: [__DIR__ . '/module/Application/src/Entity'],
    isDevMode: true,
);

try {
    $connection = DriverManager::getConnection($dbParams, $config);
    $entityManager = new EntityManager($connection, $config);
    
    ConsoleRunner::run(
        new SingleManagerProvider($entityManager)
    );
} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    echo "Please check your database settings in docker-compose.yml\n";
    exit(1);
}