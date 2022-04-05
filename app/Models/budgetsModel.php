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

    public function get_all()
    {
      return $this->jds->select([
        't' => $this->table,
        'o' => ['order:asc'],
        'm' => function($b)
        {
          $b->costs = $this->jds->select([
            't' => 'costs',
            'w' => function($c) use($b)
            {
              return (int)$c->budgets_id === (int)$b->id;
            }
          ]);
        }
      ]);
    }

    public function get_by_id($id)
    {
      return $this->jds->select([
        't' => $this->table,
        'o' => ['order:asc'],
        'm' => function($b)
        {
          $b->costs = $this->jds->select([
            't' => 'costs',
            'w' => function($c) use($b)
            {
              return (int)$c->budgets_id === (int)$b->id;
            }
          ]);
        },
        'w' => function($b) use($id)
        {
          return (int)$b->id === (int)$id;
        }
      ])[0];
    }

    public function deleteById($budgetId)
    {
      $this->jds->delete([
        't' => $this->table,
        'w' => function($b) use($budgetId)
        {
          return (int)$b->id === (int)$budgetId;
        }
      ]);
    }



    public function add()
    {
      $budget = new \stdClass();
      $sort_costs = $this->jds->select([
        't' => 'config',
        'w' => function($c)
        {
          return $c->name === 'budget_default_costs_sorting';
        }
      ])[0]->value;
      $order = $this->jds->getMaxInTable('budgets','order') + 1;
      foreach($_POST as $k => $v)
      {
        $budget->$k = $v;
      }
      $budget->sort_costs = $sort_costs;
      $budget->order = $order;
      $this->jds->insert($this->table,$budget);
    }



    public function update($budgetId)
    {
      if(isset($_POST['direction']))
      {
        $this->move($budgetId);
        return;
      }
      $this->jds->update([
        't' => $this->table,
        'w' => function($b) use($budgetId)
        {
          return (int)$b->id === (int)$budgetId;
        },
        's' => function($b)
        {
          foreach($_POST as $k => $v)
          {
            $b->$k = $v;
          }
        }
      ]);
    }



    public function move($budgetId)
    {
      $direction = $_POST['direction'];

      $budget = $this->jds->select([
        't' => $this->table,
        'w' => function($b) use($budgetId)
        {
          return $b->id === $budgetId;
        }
      ])[0];

      $maxOrdre = $this->jds->getMaxInTable('budgets','order');
      
      $budgetCurrentOrder = (int)$budget->order;

      if($direction === 'up' && $budgetCurrentOrder === 1
        || $direction === 'down' && (int)$budgetCurrentOrder === (int)$maxOrdre)
      {
        return;
      }
      
      switch($direction)
      {
        case 'up';
          // Update sibling budget
          $this->jds->update([
            't' => 'budgets',
            'w' => function($b) use($budgetCurrentOrder)
            {
              return $b->order === $budgetCurrentOrder - 1;
            },
            's' => [
              'order' => $budgetCurrentOrder
            ]
          ]);
          // Update target budget
          $this->jds->update([
            't' => 'budgets',
            'w' => function($b) use($budgetId,$budgetCurrentOrder)
            {
              return $b->id === (int)$budgetId;
            },
            's' => [
              'order' => $budgetCurrentOrder - 1
            ]
          ]);
          break;
        case 'down';
          // Update sibling budget
          $this->jds->update([
            't' => 'budgets',
            'w' => function($b) use($budgetCurrentOrder)
            {
              return $b->order === $budgetCurrentOrder + 1;
            },
            's' => [
              'order' => $budgetCurrentOrder
            ]
          ]);
          // Update target budget
          $this->jds->update([
            't' => 'budgets',
            'w' => function($b) use($budgetId,$budgetCurrentOrder)
            {
              return $b->id === (int)$budgetId;
            },
            's' => [
              'order' => $budgetCurrentOrder + 1
            ]
          ]);
          break;
      }
    }

}