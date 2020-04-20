<?php

namespace Recruitment;

use Recruitment\Services\EquipmentService;
use Recruitment\Services\PersonService;
use Recruitment\Services\ScheduleService;
use Recruitment\Services\WorkplaceService;

class Run
{
    protected $baseHtml = '';

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
                if (empty($request['value'])) {
                    $personService->save($request);
                    return;
                }
                $personService->update($request);
                return;
        }
    }

    public function prepareScreen(string $screen)
    {
        $this->preparePersonsList();
        $this->prepareWorkplacesList();
        $this->prepareEquipmentsList();
        $this->prepareScheduleList();
    }

    private function preparePersonsList()
    {
        $personService = new PersonService($this->em);
        $data = $personService->getAll();

        $scripts = 'var personsData = ' . json_encode($data) . ';
          // Fill the table
          $.each(personsData, function(personIndex, item) {
            var personData = item.id;
            var $tr = $("<tr>").append(
              $("<th scope=\'row\'>").text(item.fullname),
              $("<td class=\'update-person\' contenteditable=\'true\'>").attr("data", personData + "-phone").text(item.phone),
              $("<td class=\'update-person\' contenteditable=\'true\'>").attr("data", personData + "-email").text(item.email),
              $("<td class=\'update-person\' contenteditable=\'true\'>").attr("data", personData + "-description").text(item.description)
            ).appendTo("#persons-table");
          });

          // Add new person row
          $("#persons-table-label").click(function() {
            var $tr = $("<tr>").append(
              $("<td class=\'new-person\' contenteditable=\'true\'>").text("Lasname, Name"),
              $("<td>"),
              $("<td>"),
              $("<td>")
            ).appendTo("#persons-table");
          });

          // Update the person
          $("#persons-table").on("keypress", ".update-person", function(event) {
            if (event.keyCode !== 13) {
              return;
            }
            this.blur();
            var value = $(this).text();
            var parameters = $(this).attr("data");
            $.ajax({
              url: "index.php",
              type: "post",
              data: {
                parameters: parameters,
                value: value,
                type: "person"
              },
              error: function() {
                alert("Name is invalid!");
              },
              success: function(){
                alert("New person set!");
              }
            });
          });

          // Create new person
          $("#persons-table").on("keypress", ".new-person", function(event) {
            if (event.keyCode !== 13) {
              return;
            }

            var parameters = $(this).text();
            $.ajax({
              url: "index.php",
              type: "post",
              data: {
                parameters: parameters,
                type: "person"
              },
              error: function() {
                alert("Name is invalid!");
              },
              success: function(){
                alert("New person set!");
              }
            });
          });';
        $this->baseHtml = str_replace('//::scripts-persons::', $scripts, $this->baseHtml);
    }

    private function prepareWorkplacesList()
    {
        $workplaceService = new WorkplaceService($this->em);
        $data = $workplaceService->getAll();

        $scripts = 'var workplacesData = ' . json_encode($data) . ';
          $.each(workplacesData, function(workplaceIndex, item) {
            //var personData = item.id + "-" + workplaceIndex;
            var $tr = $("<tr>").append(
              $("<th scope=\'row\'>").text(item.designation),
              $("<td class=\'person\' contenteditable=\'true\'>").attr("data", "123").text(item.description)
            ).appendTo("#workplaces-table");
          });';
        $this->baseHtml = str_replace('//::scripts-workplaces::', $scripts, $this->baseHtml);
    }

    private function prepareEquipmentsList()
    {
        $equipmentService = new EquipmentService($this->em);
        $data = $equipmentService->getAll();

        $scripts = 'var equipmentsData = ' . json_encode($data) . ';
          $.each(equipmentsData, function(equipmentIndex, item) {
            //var personData = item.id + "-" + equipmentIndex;
            var $tr = $("<tr>").append(
              $("<th scope=\'row\'>").text(item.designation),
              $("<td>").text(item.type),
              $("<td>").text(item.description),
              $("<td class=\'person\' contenteditable=\'true\'>").attr("data", "123").text(item.workplace)
            ).appendTo("#equipments-table");
          });';
        $this->baseHtml = str_replace('//::scripts-equipments::', $scripts, $this->baseHtml);
    }

    private function prepareScheduleList()
    {
        $scheduleService = new ScheduleService($this->em);
        $data = $scheduleService->getAll();

        $tableData = '';
        foreach ($data as $key => $list) {
            $table = '<table class="table table-hover" id="schedule-table-' . str_replace('-', '', $key) . '">
              <thead class="thead-light">
              <tr><th colspan="4" class="text-left">' . $key . '</th></tr>
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

        $scripts = 'var tableData = ' . json_encode($data) . ';
          $.each(tableData, function(tableIndex, list) {
            $.each(list, function(listIndex, item) {
              var personData = item.id + "-" + tableIndex;
              var $tr = $("<tr>").append(
                $("<th scope=\'row\'>").text(item.workplace),
                $("<td>").text(item.equipment),
                $("<td>").text(tableIndex),
                $("<td class=\'schedule-person\' contenteditable=\'true\'>").attr("data", personData).text(item.person)
              ).appendTo("#schedule-table-" + tableIndex.replace(/-/g, ""));
            });
          });
          $(".schedule-person").focusout(function() {
            var value = $(this).text();
            var parameters = $(this).attr("data");
            $.ajax({
              url: "index.php",
              type: "post",
              data: {
                parameters: parameters,
                value: value,
                type: "schedule"
              },
              error: function() {
                alert("Name is invalid!");
              },
              success: function(){
                alert("New schedule set!");
              }
            });
          });';
        $this->baseHtml = str_replace('//::scripts-schedule::', $scripts, $this->baseHtml);
    }

    public function page()
    {
        echo $this->baseHtml;
    }
}
