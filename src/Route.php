<?php

namespace Antoineg\Omniscient\Core;

use Antoineg\Omniscient\Core\Exceptions\NoCallbackSetException;
use Antoineg\Omniscient\Core\Exceptions\MiddlewareNotFoundException;
use Antoineg\Omniscient\Core\Exceptions\ArrayCallbackException;
use Antoineg\Omniscient\Core\Exceptions\CallbackTypeException;
use Antoineg\Omniscient\Core\Exceptions\NotCallableCallbackException;
use Antoineg\Omniscient\Core\Exceptions\RouteNameException;
use Antoineg\Omniscient\Core\Exceptions\SingleStringArrayCallbackException;
use Antoineg\Omniscient\Core\Response;

class Route
{

    public function __construct($omniscient,$method,$uri)
    {
        $this->omniscient = $omniscient;
        $this->method = $method;
        $this->uri = $uri;
        $this->middlewares = [];
        $this->callback = null;
        $this->name = null;
        $this->exception = false;
    }

    public function middleware($middlewareName)
    {
        $middlewarePath = APP_PATH . 'Middlewares' . DS . "$middlewareName.php";
        if(!file_exists($middlewarePath))
        {
            if(AUTO_GEN_MIDDLEWARE && dev())
            {
                passthru("php omniscient middleware $middlewareName 0");
                // header('Location: ' . $this->uri);
                // exit();
            }
            else
            {
                throw new MiddlewareNotFoundException($middlewareName);
            }
        }
        $middlewareClass = "Antoineg\\Omniscient\\App\\Middlewares\\$middlewareName";
        $this->middlewares[] = new $middlewareClass($this->omniscient);
        return $this;
    }

    public function callback($callback = null)
    {
        $callbackType = gettype($callback);
        if(is_null($callback))
        {
            $this->exception = true;
            throw new CallbackTypeException($this->uri,$callbackType,true);
        }
        if(!in_array($callbackType,['string','object','array']))
        {
            $this->exception = true;
            throw new CallbackTypeException($this->uri,$callbackType);
        }
        if($callbackType === 'object' && !is_callable($callback))
        {
            $this->exception = true;
            throw new NotCallableCallbackException($this->uri);
        }
        if($callbackType === 'array')
        {
            if(count($callback) === 1)
            {
                if(gettype($callback[0]) !== 'string')
                {
                    $this->exception = true;
                    throw new SingleStringArrayCallbackException($this->uri,$callback[0]);
                }
                if(strpos($callback[0],'::') === false)
                {
                    $this->exception = true;
                    throw new SingleStringArrayCallbackException($this->uri,$callback[0]);
                }
                $callbackArray = explode('::',$callback[0]);
                $callback = $callbackArray;
            }

            if(count($callback) !== 2 && (gettype($callback[0]) !== 'string' || gettype($callback[1]) !== 'string'))
            {
                $this->exception = true;
                throw new ArrayCallbackException($this->uri);
            }
        }
        $this->callback = $callback;
        return $this;
    }

    public function name($name = '')
    {
        if(!preg_match('#^[a-zA-Z0-9\-_]*$#',$name))
        {
            $this->exception = true;
            throw new RouteNameException($this->uri,$name);
        }
        $this->name = $name;
        return $this;
    }

    public function __destruct()
    {
        if($this->exception)
        {
            return;
        }
        if(is_null($this->name))
        {
            $this->name = $this->method . str_replace('/','_',$this->uri);
        }
        if(is_null($this->callback))
        {
            if(is_null($this->omniscient->response))
            {
                $this->omniscient->set('response',new Response($this->omniscient));
            }
            throw new NoCallbackSetException($this->uri);
        }
        $this->omniscient->set_route($this->method,$this->uri,[
            'callback' => $this->callback,
            'middlewares' => $this->middlewares,
            'name' => $this->name
        ]);
    }

}