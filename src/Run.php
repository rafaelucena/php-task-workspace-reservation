<?php

namespace Recruitment;

use Recruitment\Services\PersonService;
use Recruitment\Services\ScheduleService;

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
        }
    }

    public function prepareScreen(string $screen)
    {
        $this->prepareScheduleList();
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
            var parameters = $(this).attr("data")
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
