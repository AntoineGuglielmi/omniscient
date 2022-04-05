<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class CostsController
{

    use ControllerTrait;

    public function before_action()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->model('costsModel','cosMod');
    }

    public function get_all_by_budgetId($budgetId)
    {
        $costs = $this->cosMod->jds->select([
            't' => 'costs',
            'w' => function($c) use($budgetId)
            {
                return (int)$c->budgets_id === (int)$budgetId;
            }
        ]);
        $this->api($costs);
    }
    
}