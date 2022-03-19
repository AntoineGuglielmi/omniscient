<?php

namespace Antoineg\Omniscient\Core\Exceptions;

use Exception;

class LayoutNotSetException extends Exception
{
    public function __construct()
    {
        http_response_code(500);
        $this->message = <<<HTML
L'utilisation des méthodes <code>view()</code> et <code>render()</code> d'un controller implique de définir un layout dans lequel sera injecté le contenu de la page. Actuellement, aucun layout n'est défini.<br>
Utiliser la méthode <code>set_layout(\$layoutName)</code> ou spécifier un nom de layout dans la méthode <code>render(\$layoutName)</code>.
HTML;
    }
}