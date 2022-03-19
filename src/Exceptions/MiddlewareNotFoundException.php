<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class MiddlewareNotFoundException extends Exception
{
    public function __construct($middleware)
    {
        http_response_code(500);
        $constantsFilePath = APP_PATH . 'Config' . DS . 'Constants.php';
        $this->message = <<<HTML
Le fichier associé au middleware <code>$middleware</code> n'existe pas.
HTML;
        $this->tip = <<<HTML
Lancer la commande <code>php omniscient middleware $middleware</code> pour générer le middleware.<br>
Si la constante <code>AUTO_GEN_MIDDLEWARE</code> est définie à <code>true</code> dans le fichier <code>$constantsFilePath</code>, le middleware sera généré automatiquement.
HTML;
    }
}