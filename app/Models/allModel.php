<?php

namespace Antoineg\Omniscient\App\Models;

use Antoineg\Omniscient\Core\Traits\ModelTrait;

class allModel
{

    use ModelTrait;

    public function get_all($table)
    {
      return $this->jds->select([
        't' => $table
      ]);
    }

}