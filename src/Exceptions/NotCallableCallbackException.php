<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class NotCallableCallbackException extends Exception
{
    public function __construct($uri)
    {
        http_response_code(500);
        $this->message = <<<HTML
Un callback de type <code>object</code> doit impérativement être une function.<br>
Actuellement, le callback de la route <code>$uri</code> n'est pas appelable.
HTML;
    }
}