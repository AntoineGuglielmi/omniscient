<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class model
{
    
    use CLITrait;

    private $desc = '($model,$echoResult = true) Génère le model $model. Si $echoResult est vrai, la fonction echo le résultat.';

    public function run($model,$echoResult = true)
    {
        if(!preg_match('/(m|M)odel/',$model))
        {
            $model .= 'Model';
        }
        $model = preg_replace('/(.*)model/','$1Model',$model);
        $model = preg_replace_callback('/(.*)Model/',function($m)
        {
            return ucfirst($m[1]).'Model';
        },$model);



        $content = <<<PHP
<?php

namespace Antoineg\Omniscient\App\Models;

use Antoineg\Omniscient\Core\Traits\ModelTrait;

class $model
{

    use ModelTrait;

    

}
PHP;


    
        $modelFile = realpath($this->cli::MODELS_DIR) . "\\$model.php";
        if(!file_exists($modelFile))
        {
            if(file_put_contents($modelFile,$content))
            {
                if($echoResult)
                {
                    echo $this->string("Le model $model a bien été généré.\n\n",'light_green');
                }
            }
            else
            {
                if($echoResult)
                {
                    echo $this->string("Un problème est survenu : model non généré.\n\n",'light_red');
                }
            }
        }
        else
        {
            if($echoResult)
            {
                echo $this->string("Le model $model existe déjà et ne peut être écrasé.\n\n",'light_cyan');
            }
        }
    }
    
}