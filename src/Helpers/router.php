<?php

use Antoineg\Omniscient\Core\Exceptions\RouteNotFoundException;

function get_route($routeName,...$params)
{
    global $omniscient;
    $returnedUri = null;
    $routes = $omniscient->get_routes();
    foreach($routes as $type => $typeRoutes)
    {
        foreach($typeRoutes as $method => $methodRoutes)
        {
            foreach($methodRoutes as $routeUri => $routePack)
            {
                if($routePack['name'] === $routeName)
                {
                    $returnedUri = $routeUri;
                }
            }
        }
    }
    if(is_null($returnedUri))
    {
        throw new RouteNotFoundException($routeName);
    }
    // preg_match_all('/\(([^\/]*)\)/',$returnedUri,$m);
    preg_match_all('/\{[^\/]+\}/',$returnedUri,$m);
    $regexs = $m[0];
    for($i = 0; $i < count($regexs); $i++)
    {
        $pattern = '/'.preg_quote($regexs[$i],'/').'/';
        $returnedUri = preg_replace($pattern,$params[$i],$returnedUri,1);
    }
    return $returnedUri;
}