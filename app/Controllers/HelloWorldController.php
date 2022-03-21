<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class HelloWorldController
{

    use ControllerTrait;

    public function before_action()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    public function hello_world_action()
    {
        $this->model('budMod');
        $budget = new \stdClass();
        $budget->name = 'ploup';
        $this->budMod->add_budget($budget);
        // $this->budMod->delete_budget(3);
        $this->budMod->update_budget(7);
        $budgets = $this->budMod->get_budgets();
        echo json_encode($budgets);
    }
    
}