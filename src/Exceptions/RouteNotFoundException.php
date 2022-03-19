<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class RouteNotFoundException extends Exception
{
    public function __construct($route,$e = null)
    {
        http_response_code(500);
        $appRoutesFilePath = APP_PATH . 'Routes' . DS . 'app.php';
        $apiRoutesFilePath = APP_PATH . 'Routes' . DS . 'api.php';
        // $this->title = "La route $route n'existe pas.";
        // $this->precision = "Aucune route du nom de $route n'a été définie. Vérifier les routes dans le fichier <code>". ROOT . "config/routes.php</code>.";
        $this->message = <<<HTML
La route $route n'existe pas. Vérifier les routes dans les fichiers <code>$appRoutesFilePath</code> et <code>$appRoutesFilePath</code>.
HTML;
    }
}