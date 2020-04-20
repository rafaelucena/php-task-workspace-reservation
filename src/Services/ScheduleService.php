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

        $this->delete($request);

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

    /**
     * @return array
     */
    public function getAll(): array
    {
        $today = new \DateTime();
        $tomorrow = new \DateTime('+1 day');
        $aftertomorrow = new \DateTime('+2 days');

        $dates = [
            $aftertomorrow->format('Y-m-d'),
            $tomorrow->format('Y-m-d'),
            $today->format('Y-m-d'),
        ];

        // $dates = ['2020-04-21', '2020-04-20', '2020-04-19'];
        $allWorkplaces = $this->getWorkplacesWithDate($dates);

        $last = reset($dates);
        $first = end($dates);
        $schedules = $this->getSchedules($first, $last);

        return $this->fillWorkplacesDatesWithSchedules($allWorkplaces, $schedules);
    }

    /**
     * @param array $dates
     * @return array
     */
    private function getWorkplacesWithDate(array $dates): array
    {
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

        return $mapped;
    }

    /**
     * @param string $dateFrom
     * @param string $dateUntil
     * @return array
     */
    private function getSchedules(string $dateFrom, string $dateUntil): array
    {
        $resultSet = new ResultSetMappingBuilder($this->em);
        $resultSet->addRootEntityFromClassMetadata(Schedule::class, 's');
        $sql = "SELECT
                    s.*
                FROM
                    schedules s
                WHERE
                    s.during
                    BETWEEN
                        '$dateFrom 00:00:00'
                    AND
                        '$dateUntil 00:00:00'
                ORDER BY
                    s.during DESC";
        $query = $this->em->createNativeQuery($sql, $resultSet);
        return $query->getResult();
    }

    /**
     * @param array $allWorkplaces
     * @param array $schedules
     * @return array
     */
    private function fillWorkplacesDatesWithSchedules(array $allWorkplaces, array $schedules): array
    {
        /** @var Schedule */
        foreach ($schedules as $schedule) {
            /** @var Workplace */
            $workplace = $schedule->getWorkplace();
            /** @var Person */
            $person = $schedule->getPerson();

            $allWorkplaces[$schedule->getDuring()][$workplace->getDesignation()]['during'] = $schedule->getDuring();
            $allWorkplaces[$schedule->getDuring()][$workplace->getDesignation()]['person'] = $person->getName();
        }

        return $allWorkplaces;
    }
}
