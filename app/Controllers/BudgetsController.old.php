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

    public function get_home()
    {
        $budgets = $this->budMod->get_home();

        $salaryDay = $this->budMod->jds->select([
            't' => 'config',
            'w' => function($c)
            {
              return $c->name === 'salary_day';
            }
          ])[0]->value;
        $currentDate = date('Y/m/d');
        $currentDay = date('d');
        $currentMonth = date('m');
        $currentYear = date('Y');
        $nextMonth = date('m',strtotime($currentDate . ' +1 month'));
        $nextMonthYear = date('Y',strtotime($currentDate . ' +1 month'));
        if($currentDay < $salaryDay)
        {
            $newSalaryDate = date('Y/m/d',mktime(0,0,0,$currentMonth,$salaryDay,$currentYear));
        }
        else
        {
            $newSalaryDate = date('Y/m/d',mktime(0,0,0,$nextMonth,$salaryDay,$nextMonthYear));
        }

        $daysUntilSalary = ceil((strtotime($newSalaryDate) - strtotime($currentDate)) / 86400);

        $this->api([
            'budgets' => $budgets,
            'daysUntilSalary' => $daysUntilSalary
        ]);
    }

    public function get_all()
    {
        $budgets = $this->budMod->get_all();
        $this->api($budgets);
    }

    public function get_by_id($id)
    {
        $budget = $this->budMod->get_by_id($id);
        $this->api($budget);
    }

    public function deleteById($budgetId)
    {
        $budgetId = (int) $budgetId;
        $this->budMod->deleteById($budgetId);
    }

    public function update($budgetId)
    {
        $budgetId = (int) $budgetId;
        $this->budMod->update($budgetId);
    }

    public function add()
    {
        $this->budMod->add();
    }

    // Un commentaire pour tester gitignore
    
}