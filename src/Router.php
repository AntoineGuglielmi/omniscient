<?php

namespace Antoineg\Omniscient\Core;

use Antoineg\Omniscient\Core\Exceptions\PageNotFoundException;
use Antoineg\Omniscient\Core\Exceptions\MiddlewareExecuteMethodException;
use Antoineg\Omniscient\Core\Exceptions\ControllerNotFoundException;
use Antoineg\Omniscient\Core\Exceptions\ViewNotFoundException;
use Antoineg\Omniscient\Core\Exceptions\MethodNotFoundException;

class Router
{

    private function secure($array)
    {
        // foreach($array as $k => &$v)
        // {
            // $v = filter_input_array(INPUT_GET,$k);
        // }
        return $array;
    }

    private function run_middlewares()
    {
        foreach($this->response->middlewares as $middleware)
        {
            $middlewareParams = $this->response->params;
            if(!method_exists($middleware,'execute'))
            {
                throw new MiddlewareExecuteMethodException($middleware);
            }
            call_user_func_array([$middleware,'execute'],$middlewareParams);
        }
    }

    private function run_callback()
    {
        $callback = $this->response->callback;
        $params = $this->response->params;
        $callbackType = gettype($callback);
        switch($callbackType)
        {
            case 'string':
                if(count($params))
                {
                    $data = array_combine(range(1, count($params)), array_values($params));
                }
                $data = $data ?? [];
                $callbackPath = str_replace('/','\\',VIEWS_PATH . 'pages' . DS . "$callback.php");
                if(!file_exists($callbackPath))
                {
                    if(AUTO_GEN_VIEW && dev())
                    {
                        passthru("php omniscient view $callback 0");
                        // header('Location: ' . $this->request_uri);
                        // exit();
                    }
                    else
                    {
                        throw new ViewNotFoundException($this->request_uri,$callbackPath);
                    }
                }
                echo $this->response->c_view($callback,$data);
                break;

            case 'object':
                call_user_func_array($callback,$params);
                break;

            case 'array':
                $controllerName = $callback[0];
                $controllerClass = CONTROLLERS_NAMESPACE . $controllerName;
                if(!class_exists($controllerClass))
                {
                    if(AUTO_GEN_CONTROLLER && dev())
                    {
                        passthru("php omniscient controller $controllerName {$callback[1]} 0");
                        header("Location: {$this->request_uri}");
                        exit();
                    }
                    else
                    {
                        throw new ControllerNotFoundException($controllerName,$callback[1],$this->request_uri);
                    }
                }
                $callback[0] = new $controllerClass($this->request,$this->response);
                if(!method_exists($callback[0],$callback[1]))
                {
                    throw new MethodNotFoundException($controllerName,$callback[1]);
                }
                call_user_func_array($callback,$params);
                break;
            
            default:
                // Les erreurs éventuelles sont gérées dans la fonction callback() de la classe Route
                break;
        }
    }

    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
        $this->request = $this->omniscient->request;
        $this->response = &$this->omniscient->response;
        $this->request_uri = $this->request->request_uri;
        $this->request_method = strtolower($this->request->request_method);
    }

    public function parse()
    {
        $routes = $this->omniscient->routes;
        $custom404PagePath = VIEWS_PATH . 'pages/404.php';
        if(file_exists($custom404PagePath))
        {
            $custom404PagePath = '404';
        }
        else
        {
            $custom404PagePath = '../../../src/Views/404';
        }
        
        foreach($routes as $type => $typeRoutes)
        {
            foreach($typeRoutes as $method => $methodRoutes)
            {
                if($method === $this->request_method)
                {
                    foreach($methodRoutes as $routeUri => $routePack)
                    {
                        $routePattern = preg_replace('/\{([^\/]*)\}/','(.*)',$routeUri);
                        $routePattern = "#^$routePattern$#";
                        if(preg_match($routePattern,$this->request_uri,$m))
                        {
                            $this->response->set('callback',$routePack['callback']);
                            $this->response->set('params',$this->secure(array_slice($m,1)));
                            $this->response->set('middlewares',$routePack['middlewares']);
                            break 3;
                        }
                    }
                }
            }
        }

        if(is_null($this->response->callback) && !isset($routes['app']) && !isset($routes['api']))
        {
            if(ENVIRONMENT === 'development')
            {
                $this->response->set('callback','../../../src/Views/bienvenue');
            }
            else
            {
                $this->response->set('callback',$custom404PagePath);
            }
            $this->response->set('params',[]);
            $this->response->set('middlewares',[]);
        }

        if(is_null($this->response->callback))
        {
            if(ENVIRONMENT === 'development')
            {
                throw new PageNotFoundException($this->request_uri);
            }
            else
            {
                $this->response->set('callback',$custom404PagePath);
            }
        }

        $this->run_middlewares();

        $this->run_callback();
    }

}