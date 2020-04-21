<?php

namespace Recruitment\Services;

use Recruitment\Entities\Workplace;
use Recruitment\Services\BaseService;

class WorkplaceService extends BaseService
{
    public function save(array $request)
    {
        if (empty($request['parameters']) || $this->hasHtml($request['value'] ?? '')) {
            return false;
        }

        $workplace = $this->em->getRepository(Workplace::class)->findOneBy([
            'designation' => strtolower($request['parameters']),
        ]);

        if ($workplace !== null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        $workplace = new Workplace();
        $workplace->setDesignation($request['parameters']);

        $this->em->persist($workplace);
        $this->em->flush();

        return ['status' => 'success'];
    }

    public function update(array $request)
    {
        if (empty($request['parameters']) || $this->hasHtml($request['value'] ?? '')) {
            return false;
        }

        $decoded = $this->decodeParameters($request['parameters'], 'update');
        /** @var Workplace */
        $workplace = $this->em->getRepository(Workplace::class)->find($decoded['id']);
        if ($workplace === null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        switch ($decoded['parameter']) {
            case 'designation':
                $workplace->setDesignation($request['value']);
                break;
            case 'description':
                $workplace->setDescription($request['value']);
                break;
        }

        $this->em->persist($workplace);
        $this->em->flush();

        return ['status' => 'success'];
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
        $workplaces = $this->em->getRepository(Workplace::class)->findBy([], ['designation' => 'ASC']);

        $mapped = [];
        /** @var Workplace */
        foreach ($workplaces as $workplace) {
            $mapped[] = [
                'id' => $workplace->getId(),
                'designation' => $workplace->getDesignation(),
                'description' => $workplace->getDescription(),
            ];
        }

        return $mapped;
    }
}
