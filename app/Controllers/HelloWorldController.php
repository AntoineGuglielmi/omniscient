<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class HelloWorldController
{

    use ControllerTrait;

    public function before_action()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    public function hello_world_action()
    {
        // $this->model('helloworld');
        // $data = [
        //     'foo' => 'bar'
        // ];
        // $this->view('hello-world/hello_world_action',$data);
        // $this->render('default');
        $this->model('budMod');
        // echo '<pre>';
        // var_dump($this->budMod);
        // echo '</pre>';
        $budgets = $this->budMod->get_budgets();
        echo json_encode($budgets);
    }
    
}