<?php

require 'src/bootstrap.php';

use Recruitment\Run;

$render = new Run($entityManager);
$render->prepareScreen('list-schedule');
$render->page();