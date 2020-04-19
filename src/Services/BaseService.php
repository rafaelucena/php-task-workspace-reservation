<?php

namespace Recruitment\Services;

class BaseService
{
    protected $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }
}