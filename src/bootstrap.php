<?php

require __DIR__.'/../vendor/autoload.php';

use Doctrine\ORM\Tools\Setup;
use Doctrine\ORM\EntityManager;

$paths = array("src/Entities");
$config = Setup::createAnnotationMetadataConfiguration($paths, true, null, null, false);

// the connection configuration
$dbParams = array(
    'driver' => 'pdo_sqlite',
    'path' => __DIR__ . '/../data/db.sqlite',
);

$entityManager = EntityManager::create($dbParams, $config);
