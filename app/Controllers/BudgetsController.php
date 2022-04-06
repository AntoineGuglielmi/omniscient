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
                    }
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
    
}