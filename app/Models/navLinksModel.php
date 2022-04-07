<?php

namespace Antoineg\Omniscient\App\Models;

use Antoineg\Omniscient\Core\Traits\ModelTrait;

class navLinksModel
{

    use ModelTrait;

    private $table = 'nav-links';

    public function get_all()
    {
      return $this->jds->select([
        't' => $this->table
      ]);
    }

}