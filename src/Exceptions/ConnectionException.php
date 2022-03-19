<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class ConnectionException extends Exception
{
    public function __construct($connectionName,$model)
    {
        http_response_code(500);
        $model = explode('\\',$model);
        $model = array_pop($model);
        $connectionsFile = APP_PATH . 'Config\\Connections.php';


        
        $this->message = <<<HTML
Le model $model tente d'accéder à la connexion <code>$connectionName</code>. Or, celle-ci n'existe pas.<br>
HTML;



        $this->tip = <<<HTML
Rendez-vous dans le fichier <code>$connectionsFile</code> pour définir les connexions aux bases de données.<br>
Exemple : <code>\$connection->mysql()->dbname('yourDbName')->host('yourDbHost')->user('yourUser')->pass('yourPassword')->name($connectionName);</code><br>
HTML;



    }
}