<?php

namespace Antoineg\Omniscient\Core;

use \Exception;
use Antoineg\Omniscient\Core\Exceptions\RenderException;

class Response
{

    private $omniscient;
    private $callback;
    private $params;
    private $middlewares;
    private $content;
    private $layout;
    private $layoutData = [];

    private function load_view($view,$data = [],$cache = true)
    {
        $viewPath = VIEWS_PATH . 'pages' . DS . "$view.php";
        extract($data,EXTR_PREFIX_INVALID,'omniscient');
        ob_start();
        try
        {
            require $viewPath;
        }
        catch(Exception $e)
        {
            $this->omniscient->exception($e);
        }
        if(!$cache)
        {
            return ob_get_clean();
        }
        $this->content .= ob_get_clean();
    }

    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
    }

    public function __get($prop)
    {
        return $this->$prop;
    }

    public function get($prop)
    {
        return $this->$prop;
    }

    public function set($prop,$value)
    {
        $this->$prop = $value;
    }

    public function set_layout($layout)
    {
        $this->layout = $layout;
    }

    public function view($view,$data = [])
    {
        $this->load_view($view,$data);
    }

    public function c_view($view,$data = [])
    {
        return $this->load_view($view,$data,false);
    }

    public function set_layout_data($data)
    {
        $this->layoutData = array_replace_recursive($this->layoutData,$data);
    }

    public function reset_content()
    {
        $this->content = null;
    }

    public function render($layout = null)
    {
        if(!is_null($layout))
        {
            $this->layout = $layout;
        }
        extract($this->layoutData);
        $content = $this->content;
        ob_start();
        require VIEWS_PATH . 'layouts' . DS . "{$this->layout}.php";
        $this->content = ob_get_clean();
        echo $this->content;
        // try
        // {
        //     if(!is_null($layout))
        //     {
        //         $this->layout = $layout;
        //     }
        //     extract($this->layoutData);
        //     $content = $this->content;
        //     ob_start();
        //     require VIEWS_PATH . 'layouts' . DS . "{$this->layout}.php";
        //     $this->content = ob_get_clean();
        //     echo $this->content;
        // }
        // catch(Exception $e)
        // {
        //     echo 'nope';
        //     // throw new RenderException($e);
        // }
    }

}