<?php

namespace Recruitment\Services;

use Recruitment\Entities\Workplace;
use Recruitment\Services\BaseService;

class WorkplaceService extends BaseService
{
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