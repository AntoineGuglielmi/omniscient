<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class ArrayCallbackException extends Exception
{
    public function __construct($uri)
    {
        http_response_code(500);
        $this->message = <<<HTML
Le callback défini pour l'uri <code>$uri</code> est un tableau.<br>
Les callback <code>array</code> doivent impérativement contenir 2 chaînes de caractères, représentant respectivement le nom du controller à instancier et l'action (la method) à appeler dans le controller.
HTML;
    }
}