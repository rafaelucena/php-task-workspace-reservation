<?php

require 'src/bootstrap.php';

use Recruitment\Entities\Workplace;

$workplace = $entityManager->getRepository(Workplace::class)->find(1);
$schedule = $workplace->getSchedules()->current();
$person = $schedule->getPerson();
$equipment = $workplace->getEquipment();

echo sprintf('Workplace: %s', $workplace->getDesignation()) . "<br>";
echo sprintf('Equipment: %s', $equipment->getDesignation()) . "<br>";
echo sprintf('Scheduled at: %s, for %s', $schedule->getDuring(), $person->getName()) . "<br>";
