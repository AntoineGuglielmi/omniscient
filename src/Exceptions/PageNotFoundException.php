<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class PageNotFoundException extends Exception
{
    public function __construct($uri)
    {
        http_response_code(404);
        $routesFilePath = APP_PATH . 'Routes' . DS . 'app.php';
        $this->message = <<<HTML
La page associée à l'uri <code>$uri</code> n'existe pas.<br>
La cause la plus probable reste qu'aucune route n'ait été définie pour cette uri.<br>
Retour à l'<a href="/">accueil</a>.
HTML;

        $this->tip = <<<HTML
Définir une route <code>\$app->get('$uri')->callback(function(){ echo "C'est parti !"; });</code> dans le fichier <code>$routesFilePath</code>.
HTML;
    }
}