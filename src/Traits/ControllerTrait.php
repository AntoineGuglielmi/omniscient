<?php

namespace Antoineg\Omniscient\Core\Traits;

use Antoineg\Omniscient\Core\Exceptions\LayoutNotSetException;
use Antoineg\Omniscient\Core\Exceptions\ViewNotFoundException;
use Antoineg\Omniscient\Core\Exceptions\LayoutNotFoundException;
use Antoineg\Omniscient\Core\Exceptions\ModelNotFoundException;

Trait ControllerTrait
{
    private $request;
    private $response;
    
    private function set_layout($layout)
    {
        $this->response->set_layout($layout);
    }

    private function set_layout_data($data)
    {
        $this->response->set_layout_data($data);
    }

    private function get()
    {
        return strtolower($this->request->request_method) === 'get';
    }

    private function post()
    {
        return strtolower($this->request->request_method) === 'post';
    }

    private function put()
    {
        return strtolower($this->request->request_method) === 'put';
    }

    private function head()
    {
        return strtolower($this->request->request_method) === 'head';
    }

    public function __construct($request,&$response)
    {
        $this->request = $request;
        $this->response = $response;
        $this->before_action();
    }

    public function before_action()
    {
        
    }

    public function view($view,$data = [])
    {
        $viewPath = str_replace('/','\\',VIEWS_PATH . 'pages' . DS . "$view.php");
        if(!file_exists($viewPath))
        {
            if(AUTO_GEN_VIEW && dev())
            {
                passthru("php omniscient view $view 0");
                // header('Location: ' . $this->request->request_uri);
                // exit();
            }
            else
            {
                throw new ViewNotFoundException($this->request->request_uri,$viewPath,__CLASS__,$this->response->callback[1]);
            }
        }
        $this->response->view($view,$data);
    }

    public function c_view($view,$data = [])
    {
        $viewPath = str_replace('/','\\',VIEWS_PATH . 'pages' . DS . "$view.php");
        if(!file_exists($viewPath))
        {
            if(AUTO_GEN_VIEW && dev())
            {
                passthru("php omniscient view $view 0");
                // header('Location: ' . $this->request->request_uri);
                // exit();
            }
            else
            {
                throw new ViewNotFoundException($this->request->request_uri,$viewPath,__CLASS__,$this->response->callback[1]);
            }
        }
        return $this->response->c_view($view,$data);
    }

    public function render($layout = null)
    {
        if(is_null($layout) && is_null($this->response->layout))
        {
            throw new LayoutNotSetException();
        }
        $layoutFile = str_replace('/','\\',VIEWS_PATH . 'layouts' . DS . "$layout.php");
        if(!file_exists($layoutFile))
        {
            if(AUTO_GEN_LAYOUT && dev())
            {
                passthru("php omniscient layout $layout 0");
                // header('Location: ' . $this->request->request_uri);
                // exit();
            }
            else
            {
                throw new LayoutNotFoundException($this->request->request_uri,$layoutFile,__CLASS__,__FUNCTION__);
            }
        }
        $this->response->render($layout);
    }

    public function body($prop)
    {
        return $this->request->body($prop);
    }

    public function model($modelName,$nickname = null)
    {
        if(is_null($nickname))
        {
            $nickname = $modelName;
        }
        $modelClass = "Antoineg\\Omniscient\\App\\Models\\$modelName";
        if(!class_exists($modelClass))
        {
            if(AUTO_GEN_MODEL && dev())
            {
                passthru("php omniscient model $modelName 0");
                // header("Location: {$this->request->request_uri}");
                // exit();
            }
            throw new ModelNotFoundException($modelName,__CLASS__);
        }
        $this->$nickname = new $modelClass($this->request->omniscient);
    }

    public function api($data)
    {
        echo json_encode($data);
    }
}