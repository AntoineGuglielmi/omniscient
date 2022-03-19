<?php

namespace Antoineg\Omniscient\Core;

class Singletons
{
    public $singletons = [];
    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
    }
    public function set_singleton($singletonName,$singletonValue)
    {
        $this->singletons[$singletonName] = $singletonValue;
    }
    public function get_singleton($singletonName)
    {
        return $this->singletons[$singletonName];
    }
}