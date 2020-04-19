<?php

require 'src/bootstrap.php';

use Recruitment\Run;

$render = new Run($entityManager);
if (empty($_POST) === true) {
    $render->prepareScreen('list-schedule');
    $render->page();
}

$render->evaluate($_POST);