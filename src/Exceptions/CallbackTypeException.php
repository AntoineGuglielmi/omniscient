<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class CallbackTypeException extends Exception
{
    public function __construct($uri,$type,$arg = false)
    {
        $plus = '';
        if($arg)
        {
            $plus .= '<br>Le paramètre de la fonction <code>callback()</code> ne peut être omis.';
        }
        http_response_code(500);
        $this->message = <<<HTML
Le callback d'une route ne peut être que de type <code>string</code>, <code>object</code> (<code>function</code>) ou <code>array</code>.<br>
Actuellement, le callback de la route <code>$uri</code> est de type <code>$type</code>.$plus
HTML;
    }
}