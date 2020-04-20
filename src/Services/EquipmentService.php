<?php

namespace Recruitment\Services;

use Recruitment\Entities\Equipment;
use Recruitment\Services\BaseService;

class EquipmentService extends BaseService
{
    public function save(array $request)
    {
        if (empty($request['parameters'])) {
            return false;
        }

        $equipment = $this->em->getRepository(Equipment::class)->findOneBy([
            'designation' => $request['parameters'],
        ]);

        if ($equipment !== null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        $equipment = new Equipment();
        $equipment->setDesignation($request['parameters']);

        $this->em->persist($equipment);
        $this->em->flush();

        return ['status' => 'success'];
    }

    public function update(array $request)
    {
        return;
    }

    private function decodeParameters($parameters)
    {
        $decoded = [];

        preg_match('/^(\d+)-(\w+)$/', $parameters, $match);
        $decoded['id'] = $match[1];
        $decoded['parameter'] = $match[2];

        return $decoded;
    }

    public function getAll()
    {
        $equipments = $this->em->getRepository(Equipment::class)->findBy([], []);

        $mapped = [];
        /** @var Equipment */
        foreach ($equipments as $equipment) {
            $mapped[] = [
                'id' => $equipment->getId(),
                'type' => $equipment->getType(),
                'designation' => $equipment->getDesignation(),
                'description' => $equipment->getDescription(),
                'workplace' => $equipment->getWorkplace() ? $equipment->getWorkplace()->getDesignation() : '',
            ];
        }

        return $mapped;
    }
}
