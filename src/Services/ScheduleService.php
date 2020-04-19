<?php

namespace Recruitment\Services;

use Doctrine\ORM\Query\ResultSetMappingBuilder;
use Recruitment\Entities\Equipment;
use Recruitment\Entities\Person;
use Recruitment\Entities\Schedule;
use Recruitment\Entities\Workplace;
use Recruitment\Services\BaseService;

class ScheduleService extends BaseService
{
    public function getAll()
    {
        $dates = ['2020-04-21', '2020-04-20', '2020-04-19'];
        $workplaces = $this->em->getRepository(Workplace::class)->findBy([], ['designation' => 'ASC']);

        $list = [];
        /** @var Workplace $workplace */
        foreach ($workplaces as $workplace) {
            /** @var Equipment $equipment */
            $equipment = $workplace->getEquipment();

            $list[$workplace->getDesignation()] = [
                'workplace' => $workplace->getDesignation(),
                'equipment' => $equipment->getDesignation(),
                'during' => null,
                'person' => null,
            ];
        }

        $mapped = [];
        foreach ($dates as $date) {
            $mapped[$date] = $list;
        }

        $last = reset($dates);
        $first = end($dates);
        $rsm = new ResultSetMappingBuilder($this->em);
        $rsm->addRootEntityFromClassMetadata('Recruitment\Entities\Schedule', 's');
        $sql = "SELECT s.* FROM schedules s WHERE s.during BETWEEN '$first' AND '$last' ORDER BY s.during DESC";
        $query = $this->em->createNativeQuery($sql, $rsm);
        $schedules = $query->getResult();

        /** @var Schedule */
        foreach ($schedules as $schedule) {
            /** @var Workplace */
            $workplace = $schedule->getWorkplace();
            /** @var Person */
            $person = $schedule->getPerson();

            $mapped[$schedule->getDuring()][$workplace->getDesignation()]['during'] = $schedule->getDuring();
            $mapped[$schedule->getDuring()][$workplace->getDesignation()]['person'] = $person->getName();
        }

        return $mapped;
    }
}