<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class view
{
    
    use CLITrait;

    private $desc = '($view,$echoResult = true) Génère un fichier de vue $view.php. Si $echoResult est vrai, la fonction echo le résultat.';

    public function run($view,$echoResult = true)
    {
        $viewFile = str_replace('/','\\',realpath($this->cli::VIEWS_DIR) . "\\$view.php");
        $dirToCreate = dirname($viewFile);
        $this->create_dir($dirToCreate);
        // $content = "Ready to code!";
        $content = "View crée automatiquement.<br>Modifier le contenu du fichier <code>$viewFile</code>.";


        $content = <<<HTML
<div style="
    padding: 5rem 0;
    display: flex;
    align-items: center;
    justify-content: center;
    background-image: linear-gradient(90deg,rgba(40,44,52,0.25) 0%,rgba(40,44,52,0) 50%);
">
    <div style="
        width: 100%;
        max-width: 900px;
        padding: 1.5rem;
        border-left: 1px solid rgba(40,44,52,0.5);
    ">
        <h2 style="
            font-weight: 200;
            font-size: 1.75rem;
        ">View créée automatiquement</h2>
        <p>Modifier le contenu du fichier <code style="font-weight: 700;">$viewFile</code>.</p>
    </div>
</div>
HTML;


    
        if(!file_exists($viewFile))
        {
            if(file_put_contents($viewFile,$content))
            {
                if($echoResult)
                {
                    echo $this->string("La view $view.php a bien été générée.\n\n",'light_green');
                }
            }
            else
            {
                if($echoResult)
                {
                    echo $this->string("Un problème est survenu : view non générée.\n\n",'light_red');
                }
            }
        }
        else
        {
            if($echoResult)
            {
                echo $this->string("La view $view existe déjà et ne peut être écrasée.\n\n",'light_cyan');
            }
        }
    }
    
}