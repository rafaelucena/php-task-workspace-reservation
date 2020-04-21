<?php

namespace Recruitment\Renders;

use Recruitment\Services\PersonService;

class PersonsRender
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
        $container = file_get_contents("views/list-persons.html");
        $js = file_get_contents("scripts/list-persons.js");
        $this->baseHtml = str_replace('::container::', $container, $this->baseHtml);
        $this->baseHtml = str_replace('//::scripts::', $js, $this->baseHtml);
        $this->preparePersonsList();
    }

    private function preparePersonsList()
    {
        $personService = new PersonService($this->em);
        $data = $personService->getAll();

        $scripts = 'var personsData = ' . json_encode($data) . ';';
        $this->baseHtml = str_replace('//::scripts-persons::', $scripts, $this->baseHtml);
    }

    public function page()
    {
        echo $this->baseHtml;
    }
}