<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class controller
{
    use CLITrait;

    private $desc = '($controller,$method = index,$echoResult = true) Génère le controller $controller, avec une méthode $method si elle est donnée en paramètre. Si $echoResult est vrai, la fonction echo le résultat.';

    public function run($controller,$method = 'index',$echoResult = true)
    {
        $echoResult = boolval($echoResult);
        if(!preg_match('/(c|C)ontroller/',$controller))
        {
            $controller .= 'Controller';
        }
        $controller = preg_replace('/(.*)controller/','$1Controller',$controller);
        $controller = preg_replace_callback('/(.*)Controller/',function($m)
        {
            return ucfirst($m[1]).'Controller';
        },$controller);
        
        $viewDir = str_replace('Controller','',$controller);
        $viewDir = strtolower(preg_replace('/(?<!^)[A-Z]/', '-$0',$viewDir));



        $content = <<<PHP
<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class $controller
{

    use ControllerTrait;

    public function before_action()
    {
        
    }

    public function $method()
    {
        \$data = [
            'foo' => 'bar'
        ];
        \$this->view('$viewDir/$method',\$data);
        \$this->render('default');
    }
    
}
PHP;



        $controllerFile = realpath($this->cli::CONTROLLERS_DIR) . "\\$controller.php";
        if(!file_exists($controllerFile))
        {
            if(file_put_contents($controllerFile,$content))
            {
                if($echoResult)
                {
                    echo $this->string("Le controller $controller a bien été généré.\n\n",'light_green');
                }
            }
            else
            {
                if($echoResult)
                {
                    echo $this->string("Un problème est survenu : controller non généré.\n\n",'light_red');
                }
            }
        }
        else
        {
            if($echoResult)
            {
                echo $this->string("Le controller $controller existe déjà et ne peut être écrasé.\n\n",'light_cyan');
            }
        }

    }
}