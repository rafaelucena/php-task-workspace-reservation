<?php
use Doctrine\ORM\Tools\Console\ConsoleRunner;

require_once 'src/bootstrap.php';

return ConsoleRunner::createHelperSet($entityManager);
