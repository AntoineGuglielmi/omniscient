<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class DatabaseConnectionException extends Exception
{
    public function __construct($type,$dsn)
    {
        http_response_code(500);
        $this->message = <<<HTML
La connexion <code>$type : $dsn</code> n'a pas pu être établie.
HTML;
    }
}