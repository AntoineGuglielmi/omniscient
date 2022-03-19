<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class RouteNameException extends Exception
{
    public function __construct($uri,$name)
    {
        http_response_code(500);
        $this->message = <<<HTML
Le nom d'une route ne peut contenir que des caractères alphabétiques minuscules MAJUSCULES, des chiffres, des tirets (-) et des underscores (_).<br>
Actuellement, le nom "<code>$name</code>" de la route <code>$uri</code> n'est pas valide.
HTML;
    }
}