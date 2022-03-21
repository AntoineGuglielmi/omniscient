<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class BudgetsController
{

    use ControllerTrait;

    public function before_action()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    public function get_home()
    {
        $this->model('budgetsModel','budMod');
        $budgets = $this->budMod->get_home();
        $this->api($budgets);
    }
    
}