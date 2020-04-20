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
            var $tr = $("<tr>").append(
              $("<th scope=\'row\'>").text(item.fullname),
              $("<td class=\'update-person\' contenteditable=\'true\'>")
                .attr("data", item.id + "-phone")
                .text(item.phone),
              $("<td class=\'update-person\' contenteditable=\'true\'>")
                .attr("data", item.id + "-email")
                .text(item.email),
              $("<td class=\'update-person\' contenteditable=\'true\'>")
                .attr("data", item.id + "-description")
                .text(item.description)
            ).appendTo("#persons-table");
          });

          // Add new person row
          $("#persons-table-label").click(function() {
            var $tr = $("<tr>").append(
              $("<td class=\'new-person\' contenteditable=\'true\'>")
                .text("Lasname, Name"),
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

            this.blur();
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
          // Fill the table
          $.each(workplacesData, function(workplaceIndex, item) {
            var $tr = $("<tr>").append(
              $("<th scope=\'row\'>").text(item.designation),
              $("<td class=\'update-workplace\' contenteditable=\'true\'>")
                .attr("data", item.id + "-description")
                .text(item.description)
            ).appendTo("#workplaces-table");
          });

          // Add new workplace row
          $("#workplaces-table-label").click(function() {
            var $tr = $("<tr>").append(
              $("<td class=\'new-workplace\' contenteditable=\'true\'>")
                .text("A51"),
              $("<td>")
            ).appendTo("#workplaces-table");
          });

          // Update the workplace
          $("#workplaces-table").on("keypress", ".update-workplace", function(event) {
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
                type: "workplace"
              },
              error: function() {
                alert("Name is invalid!");
              },
              success: function(){
                alert("New workplace set!");
              }
            });
          });

          // Create new workplace
          $("#workplaces-table").on("keypress", ".new-workplace", function(event) {
            if (event.keyCode !== 13) {
              return;
            }

            this.blur();
            var parameters = $(this).text();
            $.ajax({
              url: "index.php",
              type: "post",
              data: {
                parameters: parameters,
                type: "workplace"
              },
              error: function() {
                alert("Name is invalid!");
              },
              success: function(){
                alert("New workplace set!");
              }
            });
          });';
        $this->baseHtml = str_replace('//::scripts-workplaces::', $scripts, $this->baseHtml);
    }

    private function prepareEquipmentsList()
    {
        $equipmentService = new EquipmentService($this->em);
        $data = $equipmentService->getAll();

        $scripts = 'var equipmentsData = ' . json_encode($data) . ';
          // Fill the table
          $.each(equipmentsData, function(equipmentIndex, item) {
            var $tr = $("<tr>").append(
              $("<th scope=\'row\'>").text(item.designation),
              $("<td class=\'update-equipment\' contenteditable=\'true\'>")
                .attr("data", item.id + "-type")
                .text(item.type),
              $("<td class=\'update-equipment\' contenteditable=\'true\'>")
                .attr("data", item.id + "-description")
                .text(item.description),
              $("<td class=\'update-equipment\' contenteditable=\'true\'>")
                .attr("data", item.id + "-workplace")
                .text(item.workplace)
            ).appendTo("#equipments-table");
          });

          // Add new equipment row
          $("#equipments-table-label").click(function() {
            var $tr = $("<tr>").append(
              $("<td class=\'new-equipment\' contenteditable=\'true\'>")
                .text("Nokia 3220"),
              $("<td>"),
              $("<td>"),
              $("<td>")
            ).appendTo("#equipments-table");
          });

          // Update the equipment
          $("#equipments-table").on("keypress", ".update-equipment", function(event) {
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
                type: "equipment"
              },
              error: function() {
                alert("Equipment is invalid!");
              },
              success: function(){
                alert("New equipment set!");
              }
            });
          });

          // Create new equipment
          $("#equipments-table").on("keypress", ".new-equipment", function(event) {
            if (event.keyCode !== 13) {
              return;
            }

            this.blur();
            var parameters = $(this).text();
            $.ajax({
              url: "index.php",
              type: "post",
              data: {
                parameters: parameters,
                type: "equipment"
              },
              error: function() {
                alert("Equipment is invalid!");
              },
              success: function(){
                alert("New equipment set!");
              }
            });
          });';
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
          // Fill table
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

          // Edit person on the schedule table
          $(".schedule-table").on("keypress", ".schedule-person", function(event) {
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
