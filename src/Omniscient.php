<?php

namespace Antoineg\Omniscient\Core;

use Antoineg\Omniscient\Core\Request;
use Antoineg\Omniscient\Core\Response;
use Antoineg\Omniscient\Core\Router;
use Antoineg\Omniscient\Core\Config;
use Antoineg\Omniscient\Core\Singletons;
use \Exception;

class Omniscient
{
    private $request;
    private $response;
    private $router;
    private $server;
    private $config;
    private $routes;
    private $singletons;
    private $routeDefinition;

    private function routes($type)
    {
        $this->routeDefinition->currentRouteType = $type;
        return $this->routeDefinition;
    }

    private function set_server()
    {
        $serverObject = (object) $_SERVER;
        $serverArray = (array) $serverObject;
        $this->server = (object) array_combine(array_map('strtolower', array_keys($serverArray)), $serverArray);
    }

    private function set_routes()
    {
        $omniscientRoutes = $this->routes('omniscient');
        require SRC_PATH.'Routes'.DS.'OmniscientRoutes.php';

        $app = $this->routes('app');
        require APP_PATH.'Routes'.DS.'app.php';

        $api = $this->routes('api');
        require APP_PATH.'Routes'.DS.'api.php';
    }

    private function set_connections()
    {
        $connection = $this;
        require APP_PATH.'Config'.DS.'Connections.php';
    }

    public function __construct()
    {
        $this->set_server();
        $omniscient = $this;
        require 'Helpers/misc.php';
        require 'Helpers/router.php';
        $this->config = new Config($this);
        $this->request = new Request($this);
        $this->response = new Response($this);
        $this->router = new Router($this);
        $this->singletons = new Singletons($this);
        $this->routeDefinition = new RouteDefinition($this);
    }

    public function __get($prop)
    {
        if(isset($this->$prop))
        {
            return $this->$prop;
        }
        return null;
    }

    public function get_routes()
    {
        return [$this->routes['app'] ?? [],$this->routes['api'] ?? []];
    }

    public function __set($prop,$value)
    {
        if(isset($this->$prop))
        {
            $this->$prop = $value;
        }
        else
        {
            throw new Exception("<p style=\"color: red;\">Attention, il faut définir la propriété \"$prop\" avant de lui attributer une valeur !</p>", 1);
        }
    }

    public function set($prop,$value)
    {
        $this->$prop = $value;
    }

    public function go()
    {
        $this->set_routes();
        $this->set_connections();
        $this->router->parse();
    }

    public function set_route($method,$uri,$pack)
    {
        if($this->routeDefinition->currentRouteType === 'api')
        {
            $uri = "/api$uri";
        }
        $this->routes[$this->routeDefinition->currentRouteType][$method][$uri] = $pack;
    }

    public function exception($e)
    {
        $eClass = get_class($e);
        $eClass = explode('\\',$eClass);
        $data = [
            'type' => array_pop($eClass),
            'message' => $e->getMessage(),
            'trace' => $e->getTrace()
        ];
        if(isset($e->tip))
        {
            $data['tip'] = $e->tip;
        }
        if(ENVIRONMENT === 'development')
        {
            $this->response->reset_content();
            $this->response->set_layout_data($data);
            $this->response->render('../../../src/Exceptions/ExceptionsViews/exception');
        }
    }

    private function mysql($dsn = null)
    {
        $database = new Database($this,'mysql',$dsn);
        return $database;
    }

    private function jds()
    {
        $database = new Database($this,'jds',null);
        return $database;
    }
}