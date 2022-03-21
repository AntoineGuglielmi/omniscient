<?php

namespace Antoineg\Omniscient\Core\CLI;

use Antoineg\Omniscient\Core\Traits\CLITrait;

class aide
{
    use CLITrait;

    private $desc = '() Renvoie la liste des fonctions disponibles.';

    public function run()
    {
        $functions = $this->get_functions();
        $maxlen = max(array_map('strlen', $functions));
        echo $this->string("--- Liste des fonctions disponibles ---\n",'light_cyan');
        foreach($functions as $function)
        {
            $functionName = $this->string($function,'light_green');
            $spaces = str_repeat(' ',$maxlen - strlen($function) + $this->cli::SPACES);
            $descSpaces = str_repeat(' ',$maxlen + $this->cli::SPACES + 3);
            $functionClass = $this->cli::CLI_NAMESPACE . '\\' . $function;
            $functionClass = new $functionClass($this->cli);
            $functionDesc = $functionClass->desc();
            $functionDesc = str_split($functionDesc,100);
            for($i = 0; $i < count($functionDesc); $i++)
            {
                if($i === 0)
                {
                    echo "$functionName$spaces=> $functionDesc[$i]\n";
                }
                else
                {
                    echo "$descSpaces$functionDesc[$i]\n";
                }
            }
            echo "\n";
        }
        echo "\n";
    }
}