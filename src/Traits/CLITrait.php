<?php

namespace Antoineg\Omniscient\Core\Traits;

Trait CLITrait
{

    // private $desc;

    public function __construct(&$cli)
    {
        $this->cli = $cli;
    }

    private function get_functions()
    {
        return $this->cli->get_functions();
    }

    private function string($string,$fore,$back = null)
    {
        return $this->cli->string($string,$fore,$back);
    }

    public function desc($desc = null)
    {
        return $this->desc;
    }

    private function create_dir($dir)
    {
        $dirArray = explode('\\',$dir);
        $path = '';
        foreach($dirArray as $subDir)
        {
            if(empty($path))
            {
                $path = $subDir;
            }
            else
            {
                $path .= "\\$subDir";
            }
            if(!file_exists($path))
            {
                mkdir($path);
            }
        }
    }

}