<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class MethodNotFoundException extends Exception
{
    public function __construct($controllerName,$actionName)
    {
        http_response_code(500);
        $controllerDir = SRC_PATH . 'Controllers';
        $this->message = <<<HTML
Le controller <code>$controllerName</code> existe, mais ne contient pas de method <code>$actionName</code>.
HTML;
        $this->tip = <<<HTML
Implémenter une méthode <code>$actionName</code> dans le controller <code>$controllerName</code>.
HTML;
    }
}