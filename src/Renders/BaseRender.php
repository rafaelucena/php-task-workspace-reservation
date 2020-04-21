<?php

namespace Recruitment\Renders;

use Doctrine\ORM\EntityManager;
use Recruitment\Services\EquipmentService;
use Recruitment\Services\PersonService;
use Recruitment\Services\ScheduleService;
use Recruitment\Services\WorkplaceService;

class BaseRender
{
    /** @var string */
    protected $baseHtml = '';

    /** @var EntityManager */
    private $em;

    /**
     * @param EntityManager $entityManager
     */
    public function __construct(EntityManager $entityManager)
    {
        $this->em = $entityManager;
        $this->baseHtml = file_get_contents('index.html');
    }

    /**
     * @return void
     */
    public function prepareScreen(): void
    {
        //
    }

    /**
     * @return void
     */
    protected function preparePersonsList(): void
    {
        $personService = new PersonService($this->em);
        $data = $personService->getAll();

        $scripts = 'var personsData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-persons::', $scripts, $this->baseHtml);
    }

    /**
     * @return void
     */
    protected function prepareWorkplacesList(): void
    {
        $workplaceService = new WorkplaceService($this->em);
        $data = $workplaceService->getAll();

        $scripts = 'var workplacesData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-workplaces::', $scripts, $this->baseHtml);
    }

    /**
     * @return void
     */
    protected function prepareEquipmentsList(): void
    {
        $equipmentService = new EquipmentService($this->em);
        $data = $equipmentService->getAll();

        $scripts = 'var equipmentsData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-equipments::', $scripts, $this->baseHtml);
    }

    /**
     * @return void
     */
    protected function prepareScheduleList(): void
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

    /**
     * @return void
     */
    public function page(): void
    {
        echo $this->baseHtml;
    }
}
