<?php

namespace Recruitment\Services;

class BaseService
{
    protected $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
    }

    /**
     * @param string $string
     * @return boolean
     */
    protected function hasHtml(string $string): bool
    {
      return preg_match("/<[^<]+>/", $string, $match) !== 0;
    }
}
