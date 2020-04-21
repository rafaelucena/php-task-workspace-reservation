<?php

require 'src/bootstrap.php';

use Recruitment\Run;

$request = $_SERVER['REQUEST_URI'];
$run = new Run($entityManager);

if (empty($_POST) === false) {
    $run->evaluate($_POST);
    exit;
}

switch ($request) {
    case '/' :
        $run->prepare('list-all');
        break;
    case '' :
        $run->prepare('list-all');
        break;
    case '/equipments' :
        $run->prepare('list-equipments');
        break;
    default:
        http_response_code(404);
        exit;
}

$run->render();
