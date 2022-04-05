<?php

namespace Antoineg\Omniscient\App\Models;

use Antoineg\Omniscient\Core\Traits\ModelTrait;

class configsModel
{

    use ModelTrait;

    private $table = 'config';

    public function get_all()
    {
      return $this->jds->select([
        't' => $this->table
      ]);
    }

}