<?php

namespace Recruitment\Services;

use Recruitment\Entities\Equipment;
use Recruitment\Services\BaseService;

class EquipmentService extends BaseService
{
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
