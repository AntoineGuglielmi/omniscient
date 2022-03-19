<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class serve
{
    
    use CLITrait;

    private $desc = '($port = 8000) Lance le server inclu de PHP';

    public function run($port = 8000)
    {
        passthru("php -S localhost:$port");
    }
    
}