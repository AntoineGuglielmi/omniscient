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

}