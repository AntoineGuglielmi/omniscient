<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class CostsController
{

    use ControllerTrait;

    public function before_action()
    {
        // header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->model('costsModel','cosMod');
    }

    public function delete_costs_by_budget_id($budgetId)
    {
        $costs = $this->cosMod->jds->delete([
            't' => 'costs',
            'w' => function($c) use($budgetId)
            {
                return (int)$c->budgets_id === (int)$budgetId;
            }
        ]);
    }

    public function delete_cost($costId)
    {
        $this->cosMod->jds->delete([
            't' => 'costs',
            'w' => function($c) use($costId)
            {
                return (int)$c->id === (int)$costId;
            }
        ]);
    }

    public function create_cost()
    {
        $this->cosMod->create_cost();
    }
    
}