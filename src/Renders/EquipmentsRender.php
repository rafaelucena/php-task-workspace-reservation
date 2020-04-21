<?php

namespace Recruitment\Renders;

use Recruitment\Renders\BaseRender;

class EquipmentsRender extends BaseRender
{
    /**
     * @return void
     */
    public function prepareScreen(): void
    {
        $container = file_get_contents("views/list-equipments.html");
        $js = file_get_contents("scripts/list-equipments.js");
        $this->baseHtml = str_replace('::container::', $container, $this->baseHtml);
        $this->baseHtml = str_replace('//::scripts::', $js, $this->baseHtml);
        $this->prepareWorkplacesList();
        $this->prepareEquipmentsList();
    }
}
