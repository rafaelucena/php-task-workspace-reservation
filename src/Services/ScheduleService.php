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
    /**
     * @param array $request
     * @return array
     */
    public function save(array $request): array
    {
        if (empty($request['parameters']) || $this->hasHtml($request['value'] ?? '')) {
            return false;
        }

        $decoded = $this->decodeParameters($request['parameters']);
        $person = $this->em->getRepository(Person::class)->findOneBy([
            'name' => strtolower($request['value']),
        ]);

        if ($person === null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        $this->delete($request);

        $schedule = $this->em->getRepository(Schedule::class)->findOneBy([
            'during' => $decoded['date'],
            'person' => $person,
        ]);

        if ($schedule !== null) {
            header('HTTP/1.0 404 Internal Server Error');
            die;
        }

        $schedule = new Schedule();
        $schedule->setWorkplace($decoded['workplace']);
        $schedule->setPerson($person);
        $schedule->setDuring($decoded['date']);
        $schedule->setDescription('Maintenance');

        $this->em->persist($schedule);
        $this->em->flush();

        return ['status' => 'success'];
    }

    /**
     * @param array $request
     * @return void
     */
    public function delete(array $request): void
    {
        $decoded = $this->decodeParameters($request['parameters']);
        $schedule = $this->em->getRepository(Schedule::class)->findOneBy([
            'workplace' => $decoded['workplace'],
            'during' => $decoded['date'],
        ]);

        if ($schedule === null) {
            return;
        }

        $this->em->remove($schedule);
        $this->em->flush();
    }

    /**
     * @param array $parameters
     * @return array
     */
    private function decodeParameters(string $parameters): array
    {
        preg_match('/(^\d+)-(\d{4}-\d{2}-\d{2})$/', $parameters, $match);
        $decoded['workplace'] = $this->em->getRepository(Workplace::class)->find($match[1]);
        $decoded['date'] = new \DateTime($match[2]);

        return $decoded;
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
            $today->format('Y-m-d'),
            $tomorrow->format('Y-m-d'),
            $aftertomorrow->format('Y-m-d'),
        ];

        // I want the order to be the future on the top
        $allWorkplaces = $this->getWorkplacesWithDate(array_reverse($dates));

        $first = reset($dates);
        $last = end($dates);
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
                'id' => $workplace->getId(),
                'workplace' => $workplace->getDesignation(),
                'equipment' => $equipment ? $equipment->getDesignation() : '',
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
