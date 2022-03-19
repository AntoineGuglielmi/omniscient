<?php

namespace Antoineg\Omniscient\Core;

class Request
{

    private $body;

    private function set_body()
    {
        foreach($_POST as $k => $v)
        {
            $this->body[$k] = filter_input(INPUT_POST,$k,FILTER_SANITIZE_FULL_SPECIAL_CHARS);
        }
    }

    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
        $this->request_uri = $this->omniscient->server->request_uri;
        $this->request_method = $this->omniscient->server->request_method;
        $this->set_body();
    }

    public function body($key)
    {
        return $this->body[$key];
    }

}