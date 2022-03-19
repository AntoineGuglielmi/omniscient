<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class ControllerNotFoundException extends Exception
{
    public function __construct($controllerName,$action,$uri)
    {
        http_response_code(500);
        $controllerDir = APP_PATH . 'Controllers';
        $constantsFilePath = APP_PATH . 'Config' . DS . 'Constants.php';
        $this->message = <<<HTML
Le callback <code>['$controllerName','$action']</code> ou <code>['$controllerName::$action']</code> défini pour la route <code>$uri</code> est un callback de type <code>array</code>, cela signifie que la méthode <code>$action</code> du controller <code>$controllerName</code> sera appelée.<br>
Or, le controller <code>$controllerName</code> n'existe pas actuellement.<br>
Créer un fichier <code>$controllerName.php</code> dans le répertoire <code>$controllerDir</code>.
<!-- Pour une génération semi-automatique, lancer la commande <code>php omniscient controller $controllerName</code>. -->
HTML;
        $this->tip = <<<HTML
Lancer la commande <code>php omniscient controller $controllerName</code> pour générer le controller.<br>
La commande <code>php omniscient controller $controllerName $action</code> génrèra un controller ayant une méthode <code>$action</code>.<br>
Si la constante <code>AUTO_GEN_CONTROLLER</code> est définie à <code>true</code> dans le fichier <code>$constantsFilePath</code>, le controller sera généré automatiquement.
HTML;
    }
}