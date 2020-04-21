<?php

namespace Recruitment;

use Doctrine\ORM\EntityManager;
use Recruitment\Services\EquipmentService;
use Recruitment\Services\PersonService;
use Recruitment\Services\ScheduleService;
use Recruitment\Services\WorkplaceService;

class Run
{
    /** @var string */
    protected $baseHtml = '';

    /** @var EntityManager */
    private $em;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
        $this->baseHtml = file_get_contents('index.html');
    }

    public function evaluate(array $request)
    {
        if (empty($request['type'])) {
            return;
        }
        switch ($request['type']) {
            case 'schedule':
                $scheduleService = new ScheduleService($this->em);
                if (empty($request['value'])) {
                    $scheduleService->delete($request);
                    return;
                }
                $scheduleService->save($request);
                return;
            case 'person':
                $personService = new PersonService($this->em);
                if (isset($request['value']) === false) {
                    $personService->save($request);
                    return;
                }
                $personService->update($request);
                return;
            case 'workplace':
                $workplaceService = new WorkplaceService($this->em);
                if (isset($request['value']) === false) {
                    $workplaceService->save($request);
                    return;
                }
                $workplaceService->update($request);
                return;
            case 'equipment':
                $equipmentService = new EquipmentService($this->em);
                if (isset($request['value']) === false) {
                    $equipmentService->save($request);
                    return;
                }
                $equipmentService->update($request);
                return;
        }
    }

    public function prepareScreen(string $screen)
    {
        switch ($screen) {
            case 'list-all':
                $this->prepareAllLists();
                break;
        }
    }

    private function prepareAllLists()
    {
        $container = file_get_contents("views/list-all.html");
        $js = file_get_contents("scripts/list-all.js");
        $this->baseHtml = str_replace('::container::', $container, $this->baseHtml);
        $this->baseHtml = str_replace('//::scripts::', $js, $this->baseHtml);
        $this->preparePersonsList();
        $this->prepareWorkplacesList();
        $this->prepareEquipmentsList();
        $this->prepareScheduleList();
    }

    private function preparePersonsList()
    {
        $personService = new PersonService($this->em);
        $data = $personService->getAll();

        $scripts = 'var personsData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-persons::', $scripts, $this->baseHtml);
    }

    private function prepareWorkplacesList()
    {
        $workplaceService = new WorkplaceService($this->em);
        $data = $workplaceService->getAll();

        $scripts = 'var workplacesData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-workplaces::', $scripts, $this->baseHtml);
    }

    private function prepareEquipmentsList()
    {
        $equipmentService = new EquipmentService($this->em);
        $data = $equipmentService->getAll();

        $scripts = 'var equipmentsData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-equipments::', $scripts, $this->baseHtml);
    }

    private function prepareScheduleList()
    {
        $scheduleService = new ScheduleService($this->em);
        $data = $scheduleService->getAll();

        $tableData = '';
        foreach ($data as $key => $list) {
            $tableId = str_replace('-', '', $key);
            $table = '<table class="schedule-table table table-hover" id="schedule-table-' . $tableId . '">
              <thead class="thead-light">
              <tr><th colspan="4" class="text-left">Schedule ' . $key . '</th></tr>
              <tr>
                <th scope="col">Workplace</th>
                <th scope="col">Equipment</th>
                <th scope="col">During</th>
                <th scope="col">Person</th>
              </tr>
              </thead><tbody></tbody></table>';
            $tableData .= $table;
        }

        $this->baseHtml = str_replace('::data-schedule::', $tableData, $this->baseHtml);

        $scripts = 'var tableData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-schedule::', $scripts, $this->baseHtml);
    }

    public function page()
    {
        echo $this->baseHtml;
    }
}
