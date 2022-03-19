<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class SingleStringArrayCallbackException extends Exception
{
    public function __construct($uri,$callback)
    {
        http_response_code(500);
        $this->message = <<<HTML
Un callback <code>array</code> à 1 <code>string</code> doit respecter le pattern suivant : <code>NomDuController::nom_de_l_action</code>.<br>
Actuellement, le callback <code>['$callback']</code> de la route <code>$uri</code> n'est pas valide.
HTML;
    }
}