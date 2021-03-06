<?php

namespace Recruitment\Services;

use Recruitment\Entities\Equipment;
use Recruitment\Entities\Workplace;
use Recruitment\Services\BaseService;

class EquipmentService extends BaseService
{
    public function save(array $request)
    {
        if (empty($request['parameters']) || $this->hasHtml($request['value'] ?? '')) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        /** @var Equipment */
        $equipment = $this->em->getRepository(Equipment::class)->findOneBy([
            'designation' => strtolower($request['parameters']),
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
        if (empty($request['parameters']) || $this->hasHtml($request['value'] ?? '')) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        $decoded = $this->decodeParameters($request['parameters']);
        /** @var Equipment */
        $equipment = $this->em->getRepository(Equipment::class)->find($decoded['id']);
        if ($equipment === null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        if (in_array($decoded['parameter'], ['designation']) && empty($request['value']) === true) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        switch ($decoded['parameter']) {
            case 'designation':
                $equipment->setDesignation($request['value']);
                break;
            case 'model':
                $equipment->setModel($request['value']);
                break;
            case 'type':
                $equipment->setType($request['value']);
                break;
            case 'description':
                $equipment->setDescription($request['value']);
                break;
            case 'purchase-year':
                $equipment->setPurchaseYear($request['value']);
                break;
            case 'value':
                $equipment->setValue($request['value']);
                break;
            case 'workplace':
                if (empty($request['value']) === true) {
                    $equipment->setWorkplace();
                    break;
                }

                $currentDesignation = $equipment->getWorkplace() ? $equipment->getWorkplace()->getDesignation() : null;
                if ($request['value'] === $currentDesignation) {
                    return;
                }
                $workplace = $this->em->getRepository(Workplace::class)->findOneBy([
                    'designation' => strtolower($request['value']),
                ]);
                if ($workplace === null) {
                    header('HTTP/1.0 404 Internal Server Error');
                    die;
                }

                $anyequipment = $this->em->getRepository(Equipment::class)->findOneBy([
                    'workplace' => $workplace,
                ]);
                if ($anyequipment !== null) {
                    header('HTTP/1.0 404 Internal Server Error');
                    die;
                }

                $equipment->setWorkplace($workplace);
                break;
        }

        $this->em->persist($equipment);
        $this->em->flush();

        return ['status' => 'success'];
    }

    private function decodeParameters($parameters)
    {
        $decoded = [];

        preg_match('/^(\d+)-(\w+-?\w+?)$/', $parameters, $match);
        $decoded['id'] = $match[1];
        $decoded['parameter'] = $match[2];

        return $decoded;
    }

    public function getAll()
    {
        $equipments = $this->em->getRepository(Equipment::class)->findBy([], ['designation' => 'ASC']);

        $mapped = [];
        /** @var Equipment */
        foreach ($equipments as $equipment) {
            $mapped[] = [
                'id' => $equipment->getId(),
                'type' => $equipment->getType(),
                'designation' => $equipment->getDesignation(),
                'model' => $equipment->getModel(),
                'description' => $equipment->getDescription(),
                'value' => $equipment->getValue(),
                'purchaseYear' => $equipment->getPurchaseYear(),
                'workplace' => $equipment->getWorkplace() ? $equipment->getWorkplace()->getDesignation() : '',
            ];
        }

        return $mapped;
    }
}
