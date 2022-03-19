<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class NoCallbackSetException extends Exception
{
    public function __construct($uri)
    {
        http_response_code(500);
        $this->message = <<<HTML
Aucun callback n'a été défini pour la route <code>$uri</code>. Utiliser la méthode <code>callback(\$callback)</code>.<br>
Exemple : <code>\$omniscient->get('$uri')->callback(function(){ echo 'Hello, world!'; });</code>
HTML;
    }
}