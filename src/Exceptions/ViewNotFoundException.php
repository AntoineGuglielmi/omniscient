<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class ViewNotFoundException extends Exception
{
    public function __construct($uri,$viewPath,$controller = null,$action = null)
    {
        http_response_code(500);
        $controller = explode('\\',$controller);
        $controller = array_pop($controller);
        $viewBasename = basename($viewPath,'.php');
        $viewDir = dirname($viewPath);
        $constantsFilePath = APP_PATH . 'Config' . DS . 'Constants.php';
        $viewName = str_replace([VIEWS_PATH . 'pages\\','.php'],['',''],$viewPath);
        
        if(!is_null($controller))
        {
            $this->message .= <<<HTML
La méthode <code>$action</code> du controller <code>$controller</code> essaye de charger le fichier view <code>$viewPath</code>.
Or, ce fichier n'existe pas actuellement.<br>
Créer un fichier <code>$viewBasename.php</code> dans le répertoire <code>$viewDir</code>.
HTML;
        }
        else
        {
            $this->message .= <<<HTML
Le callback <code>'$viewBasename'</code> défini pour la route <code>$uri</code> est un callback de type <code>string</code>, cela signifie que le fichier <code>$viewPath</code> sera injecté dans la réponse.<br>
Or, ce fichier n'existe pas actuellement.<br>
Créer un fichier <code>$viewBasename.php</code> dans le répertoire <code>$viewDir</code>.
HTML;
        }

        $this->tip = <<<HTML
Lancer la commande <code>php omniscient view $viewName</code> pour générer la view.<br>
Si la constante <code>AUTO_GEN_VIEW</code> est définie à <code>true</code> dans le fichier <code>$constantsFilePath</code>, la view sera générée automatiquement.
HTML;
    }
}