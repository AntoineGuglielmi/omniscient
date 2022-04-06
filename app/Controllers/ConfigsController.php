<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class ConfigsController
{

    use ControllerTrait;

    public function before_action()
    {
        // header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->model('configsModel','conMod');
    }

    public function get_all()
    {
        $configs = $this->conMod->get_all();
        $this->api($configs);
    }
    
}