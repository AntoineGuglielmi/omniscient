<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class NavLinksController
{

    use ControllerTrait;

    public function before_action()
    {
        // header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->model('navLinksModel','navMod');
    }

    public function get_all()
    {
        $navLinks = $this->conMod->get_all();
        $this->api($navLinks);
    }
    
}