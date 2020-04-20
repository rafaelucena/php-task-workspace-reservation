<?php

namespace Recruitment\Services;

use Recruitment\Entities\Person;
use Recruitment\Services\BaseService;

class PersonService extends BaseService
{
    public function getAll()
    {
        $mapped = [];
        $persons = $this->em->getRepository(Person::class)->findBy([], ['lastname' => 'ASC', 'name' => 'ASC']);
        /** @var Person */
        foreach ($persons as $person) {
            $mapped[] = [
                'id' => $person->getId(),
                'fullname' => $person->getLastname() . ', ' . $person->getName(),
                'phone' => $person->getPhone(),
                'email' => $person->getEmail(),
                'description' => $person->getDescription(),
            ];
        }

        return $mapped;
    }
}