<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class middleware
{
    use CLITrait;

    private $desc = "(\$middleware,\$echoResult = true) Génère le middleware \$middleware. Si \$echoResult est vrai, la fonction echo le résultat.";

    public function run($middleware,$echoResult = true)
    {
        if(!preg_match('/(m|M)iddleware/',$middleware))
        {
            $middleware .= 'Middleware';
        }
        $middleware = preg_replace('/(.*)Middleware/','$1Middleware',$middleware);
        $middleware = preg_replace_callback('/(.*)Middleware/',function($m)
        {
            return ucfirst($m[1]).'Middleware';
        },$middleware);
        
        

        $content = <<<PHP
<?php

namespace Antoineg\Omniscient\App\Middlewares;

use Antoineg\Omniscient\Core\Traits\MiddlewareTrait;

class $middleware
{

    use MiddlewareTrait;

    public function execute()
    {
        
    }

}
PHP;



        $middlewareFile = realpath($this->cli::MIDDLEWARES_DIR) . "\\$middleware.php";
        if(file_exists($middlewareFile))
        {
            if($echoResult)
            {
                echo $this->string("Le Middleware $middleware existe déjà.\n\n",'light_red');
            }
        }
        else
        {
            if(file_put_contents($middlewareFile,$content))
            {
                if($echoResult)
                {
                    echo $this->string("Le Middleware $middleware a bien été généré.\n\n",'light_green');
                }
            }
            else
            {
                if($echoResult)
                {
                    echo $this->string("Un problème est survenu : Middleware non généré.\n\n",'light_red');
                }
            }
        }

    }
}