<?php

namespace Antoineg\Omniscient\Core\Traits;

Trait MiddlewareTrait
{

    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
    }

    // public function execute()
    // {
    //     echo 'Définir une méthode execute dans le middleware de la route !';
    // }

}