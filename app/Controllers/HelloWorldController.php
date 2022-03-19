<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class HelloWorldController
{

    use ControllerTrait;

    public function before_action()
    {
        
    }

    public function hello_world_action()
    {
        // $this->model('helloworld');
        $data = [
            'foo' => 'bar'
        ];
        $this->view('hello-world/hello_world_action',$data);
        $this->render('default');
    }
    
}