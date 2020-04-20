<?php

namespace Recruitment\Services;

use Recruitment\Entities\Person;
use Recruitment\Services\BaseService;

class PersonService extends BaseService
{
    public function save(array $request)
    {
        if (empty($request['parameters'])) {
            return false;
        }

        $decoded = $this->decodeParameters($request['parameters'], 'save');
        $person = $this->em->getRepository(Person::class)->findOneBy([
            'name' => $decoded['name'],
        ]);

        if ($person !== null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        $person = new Person();
        $person->setName($decoded['name']);
        $person->setLastname($decoded['lastname']);

        $this->em->persist($person);
        $this->em->flush();

        return ['status' => 'success'];
    }

    public function update(array $request)
    {
        if (empty($request['parameters'])) {
            return false;
        }

        $decoded = $this->decodeParameters($request['parameters'], 'update');
        /** @var Person */
        $person = $this->em->getRepository(Person::class)->find($decoded['id']);
        if ($person === null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        switch ($decoded['parameter']) {
            case 'phone':
                $person->setPhone($request['value']);
                break;
            case 'email':
                $person->setEmail($request['value']);
                break;
            case 'description':
                $person->setDescription($request['value']);
                break;
        }

        $this->em->persist($person);
        $this->em->flush();

        return ['status' => 'success'];
    }

    private function decodeParameters($parameters, $type)
    {
        $decoded = [];

        if ($type === 'save') {
            preg_match('/^(\w+).+?(\w+)$/', $parameters, $match);
            $decoded['lastname'] = $match[1];
            $decoded['name'] = $match[2];
        } elseif ($type === 'update') {
            preg_match('/^(\d+)-(\w+)$/', $parameters, $match);
            $decoded['id'] = $match[1];
            $decoded['parameter'] = $match[2];
        }

        return $decoded;
    }

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