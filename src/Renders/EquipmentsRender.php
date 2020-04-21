<?php

namespace Recruitment\Renders;

use Recruitment\Services\EquipmentService;
use Recruitment\Services\WorkplaceService;

class EquipmentsRender
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
        $container = file_get_contents("views/list-equipments.html");
        $js = file_get_contents("scripts/list-equipments.js");
        $this->baseHtml = str_replace('::container::', $container, $this->baseHtml);
        $this->baseHtml = str_replace('//::scripts::', $js, $this->baseHtml);
        $this->prepareWorkplacesList();
        $this->prepareEquipmentsList();
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

    public function page()
    {
        echo $this->baseHtml;
    }
}