<?php

require 'src/bootstrap.php';

use Recruitment\Entities\Equipment;
use Recruitment\Entities\Person;
use Recruitment\Entities\Schedule;
use Recruitment\Entities\Workplace;

$dateTime = new \DateTime('today');
$dateTime->setTime(0, 0);

// First wave
$person = new Person();
$person->setName('Douglas');
$person->setLastname('Adams');
$person->setPhone('601271332');
$person->setEmail('douglas.adams@test.com');
$person->setDescription('Look about the stars, wonder who we are');

$workplace = new Workplace();
$workplace->setDesignation('A1');
$workplace->setDescription('Kapusta');

$equipment = new Equipment();
$equipment->setType('Linux');
$equipment->setDesignation('Dell Inspiron');
$equipment->setPurchaseYear(2015);
$equipment->setValue(2099);
$equipment->setDescription('Overheating a little');
$equipment->setWorkplace($workplace);

$schedule = new Schedule();
$schedule->setDuring($dateTime);
$schedule->setDescription('Interview');
$schedule->setWorkplace($workplace);
$schedule->setPerson($person);

$entityManager->persist($person);
$entityManager->persist($workplace);
$entityManager->persist($equipment);
$entityManager->persist($schedule);
$entityManager->flush();

// Second wave
$person = new Person();
$person->setName('Terry');
$person->setLastname('Pratchett');
$person->setPhone('605927512');
$person->setEmail('terry.pratchett@test.com');
$person->setDescription('Rincewind would be proud');

$workplace = new Workplace();
$workplace->setDesignation('B1');
$workplace->setDescription('Pomidor');

$equipment = new Equipment();
$equipment->setType('Windows');
$equipment->setDesignation('HP Envy');
$equipment->setPurchaseYear(2011);
$equipment->setValue(1799);
$equipment->setDescription('Overheating a LOT');
$equipment->setWorkplace($workplace);

$schedule = new Schedule();
$schedule->setDuring($dateTime->modify('+1 day'));
$schedule->setDescription('Interview');
$schedule->setWorkplace($workplace);
$schedule->setPerson($person);

$entityManager->persist($person);
$entityManager->persist($workplace);
$entityManager->persist($equipment);
$entityManager->persist($schedule);
$entityManager->flush();

// Third wave
$person = new Person();
$person->setName('Neil');
$person->setLastname('Gaiman');
$person->setPhone('601071232');
$person->setEmail('neil.gaiman@test.com');
$person->setDescription('Time is fluid here');

$workplace = new Workplace();
$workplace->setDesignation('A2');
$workplace->setDescription('Malina');

$equipment = new Equipment();
$equipment->setType('Linux');
$equipment->setDesignation('Dell Vostro');
$equipment->setPurchaseYear(2018);
$equipment->setValue(2599);
$equipment->setDescription('Slow on startup');
$equipment->setWorkplace($workplace);

$schedule = new Schedule();
$schedule->setDuring($dateTime->modify('+1 day'));
$schedule->setDescription('Maintenance');
$schedule->setWorkplace($workplace);
$schedule->setPerson($person);

$entityManager->persist($person);
$entityManager->persist($workplace);
$entityManager->persist($equipment);
$entityManager->persist($schedule);
$entityManager->flush();

// Fourth wave
$schedule = new Schedule();
$schedule->setDuring($dateTime->modify('-1 day'));
$schedule->setDescription('Maintenance');
$schedule->setWorkplace($workplace);
$schedule->setPerson($person);

$entityManager->persist($schedule);
$entityManager->flush();