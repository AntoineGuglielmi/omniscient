<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class MiddlewareExecuteMethodException extends Exception
{
    public function __construct($middleware)
    {
        $middlewareName = explode('\\',get_class($middleware));
        $middlewareName = array_pop($middlewareName);
        http_response_code(500);
        $this->message = <<<HTML
Un middleware doit impérativement implémenter une méthode <code>execute()</code> afin d'être activé.<br>
Actuellement, le middleware <code>$middlewareName</code> n'a pas de method <code>execute()</code>.
HTML;
    }
}