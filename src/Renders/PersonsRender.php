<?php

namespace Recruitment\Renders;

use Recruitment\Renders\BaseRender;

class PersonsRender extends BaseRender
{
    /**
     * @return void
     */
    public function prepareScreen(): void
    {
        $container = file_get_contents("views/list-persons.html");
        $js = file_get_contents("scripts/list-persons.js");
        $this->baseHtml = str_replace('::container::', $container, $this->baseHtml);
        $this->baseHtml = str_replace('//::scripts::', $js, $this->baseHtml);
        $this->preparePersonsList();
    }
}
