<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class jds
{
    
    use CLITrait;

    private $desc = '(table,:action) ...';

    public function run(...$args)
    {
        foreach($args as $arg)
        {
            if(substr($arg,0,1) === ':')
            {
                $action = substr($arg,1);
            }
            else
            {
                $table = $arg;
            }
        }

        if($action === 'truncate')
        {
            $tableData = <<<JSON
{
    "liid": 0,
    "data": []
}
JSON;
        file_put_contents($this->cli::APP_PATH.'JDS/'.$table.'.json',$tableData);
        }
    }
    
}