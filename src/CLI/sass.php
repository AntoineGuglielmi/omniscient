<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class sass
{
    
    use CLITrait;

    private $desc = '($root = public) Génère une arborescence sass / css à $root';

    public function run($root = 'public')
    {
        $styles = "$root\\styles";
        $sass = "$styles\\sass";
        $css = "$styles\\css";
        $mainSass = "$sass\\main.sass";
        $dirs = [$styles,$sass,$css];
        foreach($dirs as $dir)
        {
            if(!file_exists($dir))
            {
                mkdir($dir);
            }
        }
        if(!file_exists($mainSass))
        {
            file_put_contents($mainSass,'');
        }
        passthru("sass --watch $sass:$css --style expanded");
    }
    
}