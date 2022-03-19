<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class command
{
    use CLITrait;

    private $desc = '($command) Génère une nouvelle class $command de fonction terminal.';

    public function run($command)
    {
        $commandPath = realpath($this->cli::CLI_DIR) . "/$command.php";



        $commandContent = <<<PHP
<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class $command
{
    
    use CLITrait;

    private \$desc = '() ...';

    public function run()
    {
        
    }
    
}
PHP;



        if(!file_exists($commandPath))
        {
            if(file_put_contents($commandPath,$commandContent))
            {
                echo $this->string("La command \"$command\" a bien été générée.\n\n",'light_green');
            }
            else
            {
                echo $this->string("Un problème est survenu lors de la génération de la commande \"$command\".\n\n",'light_red');
            }
        }
        else
        {
            echo $this->string("La commande \"$command\" existe déjà et ne peut être écrasée.\n\n",'light_cyan');
        }
    }
}