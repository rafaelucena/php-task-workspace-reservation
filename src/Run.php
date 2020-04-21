<?php

namespace Recruitment;

use Doctrine\ORM\EntityManager;
use Recruitment\Renders\EquipmentsRender;
use Recruitment\Renders\HomeRender;
use Recruitment\Renders\PersonsRender;
use Recruitment\Services\EquipmentService;
use Recruitment\Services\PersonService;
use Recruitment\Services\ScheduleService;
use Recruitment\Services\WorkplaceService;

class Run
{
    /** @var EntityManager */
    private $em;

    private $render;

    public function __construct($entityManager)
    {
        $this->em = $entityManager;
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

    public function prepare(string $screen)
    {
        switch ($screen) {
            case 'list-all':
                $this->render = new HomeRender($this->em);
                $this->render->prepareScreen();
                break;
            case 'list-equipments':
                $this->render = new EquipmentsRender($this->em);
                $this->render->prepareScreen();
                break;
            case 'list-persons':
                $this->render = new PersonsRender($this->em);
                $this->render->prepareScreen();
                break;
        }
    }

    public function render()
    {
        echo $this->render->page();
    }
}
