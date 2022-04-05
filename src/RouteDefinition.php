<?php

namespace Antoineg\Omniscient\Core;

class RouteDefinition
{

    public $currentRouteType;

    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
    }

    public function get($uri)
    {
        $route = new Route($this->omniscient,'get',$uri);
        return $route;
    }

    public function post($uri)
    {
        $route = new Route($this->omniscient,'post',$uri);
        return $route;
    }

    public function delete($uri)
    {
        $route = new Route($this->omniscient,'delete',$uri);
        return $route;
    }

    public function put($uri)
    {
        $route = new Route($this->omniscient,'put',$uri);
        return $route;
    }

}