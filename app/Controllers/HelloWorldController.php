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

    public function hello_world_action($table)
    {
        $modelName = $table.'Model';
        $this->model($modelName);

        // $budget = new \stdClass();
        // $budget->name = 'Prélèvements';
        // $budget->saving = false;
        // $budget->limit = 0;
        // $budget->flux = 'Débit';
        // $budget->sortDepenses = 'amount:desc';
        // $budget->ordre = $this->budMod->jds->getMaxInTable('budgets','ordre') + 1;
        // $this->budMod->add_budget($budget);

        // $this->budMod->delete_budget(3);

        // $this->budMod->update_budget(7);

        $result = $this->$modelName->get();
        $this->api($result);
    }
    
}