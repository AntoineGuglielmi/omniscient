<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class RenderException extends Exception
{
    public function __construct($e)
    {
        http_response_code(500);
        $this->message = <<<HTML
Soucis...
HTML;
    }
}