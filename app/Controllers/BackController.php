<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class BackController
{

    use ControllerTrait;

    public function before_action()
    {
        
    }

    public function insert_random_cost()
    {
        $this->model('costsModel','cosMod');
        $this->cosMod->insert_rand();
        $this->api($this->cosMod->get());
    }
    
}