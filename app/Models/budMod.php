<?php

namespace Antoineg\Omniscient\App\Models;

use Antoineg\Omniscient\Core\Traits\ModelTrait;

class budMod
{

    use ModelTrait;

    public function get_budgets()
    {
      return $this->jds->select([
        't' => 'budgets'
      ]);
    }

    public function add_budget($budget)
    {
      $this->jds->insert('budgets',$budget);
    }

    public function delete_budget($id = null)
    {
      $this->jds->delete([
        't' => 'budgets',
        'w' => function($b) use($id)
        {
          return $b->id === $id;
        }
      ]);
    }

    public function update_budget($id = null)
    {
      $this->jds->update([
        't' => 'budgets',
        's' => [
          'name' => 'Plooouuup'
        ],
        'w' => function($b) use($id)
        {
          return $b->id === $id;
        }
      ]);
    }

}