<?php

namespace Antoineg\Omniscient\App\Models;

use Antoineg\Omniscient\Core\Traits\ModelTrait;

class budgetsModel
{

    use ModelTrait;

    private $table = 'budgets';

    private function getTotalAmount($budget)
    {
      if(gettype($budget) === 'object')
      {
        $budget = $budget->id;
      }
      $costs = $this->jds->select([
        't' => 'costs',
        'w' => function($c) use($budget)
        {
          return $c->budgetId === $budget;
        }
      ]);
      return array_sum(array_column($costs, 'amount'));
    }

    public function get()
    {
      return $this->jds->select([
        't' => $this->table
      ]);
    }

    public function add_budget($budget)
    {
      $this->jds->insert('budgets',$budget);
    }

    public function delete_budget($id = null)
    {
      $this->jds->delete([
        't' => $this->table,
        'w' => function($b) use($id)
        {
          return $b->id === $id;
        }
      ]);
    }

    public function update_budget($id = null)
    {
      $this->jds->update([
        't' => $this->table,
        's' => [
          'name' => 'Plooouuup'
        ],
        'w' => function($b) use($id)
        {
          return $b->id === $id;
        }
      ]);
    }

    public function get_home()
    {
      // Le jour de paiement du salaire
      $salaryDay = $this->jds->select([
        't' => 'config',
        'w' => function($c)
        {
          return $c->name === 'salary_day';
        }
      ])[0]->value;

      // La date du prochain salaire
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

      return $this->jds->select([
        't' => $this->table,
        'm' => function($b) use($salaryDay,$currentDate,$nextMonth,$newSalaryDate,$daysUntilSalary)
        {
          // $b->costs = $this->jds->select([
          //   't' => 'costs',
          //   'w' => function($c) use($b)
          //   {
          //     return $c->budgetId === $b->id;
          //   },
          //   'o' => [$b->sortCosts]
          // ]);
          $b->total = $this->getTotalAmount($b);
          $b->progress = $b->limit ? $b->total / $b->limit * 100 : null;
          $b->maxPerDay = $b->limit ? ($b->limit - $b->total) / $daysUntilSalary : null;
        }
      ]);
    }

}