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
    public function save(array $request)
    {
        if (empty($request['parameters'])) {
            return false;
        }

        preg_match('/\d{4}-\d{2}-\d{2}$/', $request['parameters'], $match);
        $date = new \DateTime($match[0]);

        preg_match('/^\d+/', $request['parameters'], $match);
        $workplaceId = $match[0];

        $person = $this->em->getRepository(Person::class)->findOneBy([
            'name' => $request['value'],
        ]);

        if ($person === null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        $workplace = $this->em->getRepository(Workplace::class)->find($workplaceId);

        $schedule = new Schedule();
        $schedule->setWorkplace($workplace);
        $schedule->setPerson($person);
        $schedule->setDuring($date);
        $schedule->setDescription('Maintenance');

        $this->em->persist($schedule);
        $this->em->flush();

        return ['status' => 'success'];
    }

    public function delete(array $request)
    {
        preg_match('/\d{4}-\d{2}-\d{2}$/', $request['parameters'], $match);
        $date = new \DateTime($match[0]);

        preg_match('/^\d+/', $request['parameters'], $match);
        $workplaceId = $match[0];

        $workplace = $this->em->getRepository(Workplace::class)->find($workplaceId);

        $schedule = $this->em->getRepository(Schedule::class)->findOneBy([
            'workplace' => $workplace,
            'during' => $date,
        ]);

        $this->em->remove($schedule);
        $this->em->flush();
    }

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
                'data' => $workplace->getId(),
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
        $sql = "SELECT s.* FROM schedules s WHERE s.during BETWEEN '$first 00:00:00' AND '$last 00:00:00' ORDER BY s.during DESC";
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