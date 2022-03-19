<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class ModelNotFoundException extends Exception
{
    public function __construct($model,$controller)
    {
        $controller = explode('\\',$controller);
        $controller = array_pop($controller);
        $controllerDir = APP_PATH . 'Models';
        $constantsFilePath = APP_PATH . 'Config' . DS . 'Constants.php';
        http_response_code(500);
        $this->message = <<<HTML
Le controller $controller tente d'accéder au model $model. Or, ce dernier n'existe pas actuellement.<br>
Créer un fichier $model.php dans le répertoire $controllerDir.
HTML;
        $this->tip = <<<HTML
Lancer la commande <code>php omniscient model $model</code> pour générer le model.<br>
Si la constante <code>AUTO_GEN_MODEL</code> est définie à <code>true</code> dans le fichier <code>$constantsFilePath</code>, le model sera généré automatiquement.
HTML;
    }
}