<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class LayoutNotFoundException extends Exception
{
    public function __construct($uri,$layoutPath,$controller = null,$method = null)
    {
        http_response_code(500);
        $controller = explode('\\',$controller);
        $controller = array_pop($controller);
        $layoutBasename = basename($layoutPath,'.php');
        $layoutDir = dirname($layoutPath);
        $constantsFilePath = APP_PATH . 'Config' . DS . 'Constants.php';
        $layoutName = str_replace([VIEWS_PATH . 'layouts\\','.php'],['',''],$layoutPath);
        
        // if(!is_null($controller))
        // {
            $this->message .= <<<HTML
La méthode <code>$method</code> du controller <code>$controller</code> essaye de charger le layout <code>$layoutPath</code>.
Or, ce fichier n'existe pas actuellement.<br>
Créer un fichier <code>$layoutBasename.php</code> dans le répertoire <code>$layoutDir</code>.
HTML;
        // }
//         else
//         {
//             $this->message .= <<<HTML
// Le callback <code>'$layoutBasename'</code> défini pour la route <code>$uri</code> est un callback de type <code>string</code>, cela signifie que le fichier <code>$layoutPath</code> sera injecté dans la réponse.<br>
// Or, ce fichier n'existe pas actuellement.<br>
// Créer un fichier <code>$layoutBasename.php</code> dans le répertoire <code>$layoutDir</code>.
// HTML;
//         }

        $this->tip = <<<HTML
Lancer la commande <code>php omniscient layout $layoutName</code> pour générer le layout.<br>
Si la constante <code>AUTO_GEN_LAYOUT</code> est définie à <code>true</code> dans le fichier <code>$constantsFilePath</code>, le layout sera généré automatiquement.
HTML;
    }
}