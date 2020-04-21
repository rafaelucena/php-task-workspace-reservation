<?php

namespace Recruitment\Renders;

use Recruitment\Services\EquipmentService;
use Recruitment\Services\PersonService;
use Recruitment\Services\ScheduleService;
use Recruitment\Services\WorkplaceService;

class HomeRender
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

    public function prepareScreen()
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
              <tr><th colspan="4" class="text-left">Plan ' . $key . '</th></tr>
              <tr>
                <th scope="col">Miejsce pracy</th>
                <th scope="col">Wyposażenie</th>
                <th scope="col">Kiedy</th>
                <th scope="col">Osoba</th>
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