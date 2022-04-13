<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class BudgetsController
{

    use ControllerTrait;

    public function before_action()
    {
        // header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
        $this->model('budgetsModel','budMod');
    }

    public function get_budgets($id = null)
    {
        $requestParams = [
            't' => 'budgets',
            'o' => ['order:asc'],
            'm' => function($b)
            {
                $b->costs = $this->budMod->jds->select([
                    't' => 'costs',
                    'w' => function($c) use($b)
                    {
                        return (int)$c->budgets_id === (int)$b->id;
                    },
                    'o' => [$b->sort_costs]
                ]);
            }
        ];
        if(!is_null($id)) {
            $requestParams['w'] = function($b) use($id)
            {
                return (int)$b->id === (int)$id;
            };
        }
        $this->api($this->budMod->jds->select($requestParams));
    }

    public function delete_budget($budgetId)
    {
        $this->budMod->jds->delete([
            't' => 'costs',
            'w' => function($c) use($budgetId)
            {
                return (int)$c->budgets_id === (int)$budgetId;
            }
        ]);
        $this->budMod->jds->delete([
            't' => 'budgets',
            'w' => function($b) use($budgetId)
            {
                return (int)$b->id === (int)$budgetId;
            }
        ]);
    }

    public function update_budget($id)
    {
        $putData = json_decode(file_get_contents('php://input'));
        $id = (int) $id;
        $this->budMod->update($id,(array)$putData);
    }

    public function create_budgets()
    {
        $this->budMod->create_budget();
    }
    
}