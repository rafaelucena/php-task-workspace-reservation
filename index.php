<?php

require 'src/bootstrap.php';

use Recruitment\Run;

$request = $_SERVER['REQUEST_URI'];
$render = new Run($entityManager);

if (empty($_POST) === false) {
    $render->evaluate($_POST);
    exit;
}

switch ($request) {
    case '/' :
        $render->prepareScreen('list-all');
        break;
    case '' :
        $render->prepareScreen('list-all');
        break;
    case '/equipments' :
        $render->prepareScreen('list-equipments');
        break;
    default:
        http_response_code(404);
        require __DIR__ . '/views/404.php';
        break;
}

$render->page();