<?php

namespace Antoineg\Omniscient\App\Controllers;

use Antoineg\Omniscient\Core\Traits\ControllerTrait;

class ApiController
{

    use ControllerTrait;

    public function before_action()
    {
        header('Access-Control-Allow-Origin: *');
        header('Content-Type: application/json');
    }

    public function teeest($query)
    {
        $this->model('budgetsModel','budMod');
        $result = [];
        $decodedUrl = urldecode($query);
        $decodedUrl = preg_replace('/([ ]*)([\{])([ ]*)/','$2',$decodedUrl);
        $decodedUrl = preg_replace('/([ ]*)([\}])([ ]*)/','$2 ',$decodedUrl);
        $decodedUrl = preg_replace('/([ ]*)([\}])/','$2',$decodedUrl);
        $decodedUrl = preg_replace('/([ ]+)/',' ',$decodedUrl);

        $this->corr = [];
        $count = 0;

        // echo $decodedUrl."\n\n";

        while(preg_match('/([a-zA-Z0-9()]+){([a-zA-Z :0-9*]*)}/',$decodedUrl,$m))
        {
            $count++;
            $uid = "::$count::";
            $this->corr[$uid] = $m[0];
            $decodedUrl = str_replace($m[0],$uid,$decodedUrl);
        }

        $newQuery = $this->corr["::$count::"];

        // ----------------------------------------------------------
        preg_match('/([a-zA-Z0-9()]+){([a-zA-Z0-9: *]+)}/',$newQuery,$m);

        $tableRaw = $m[1];
        preg_match('/([a-zA-Z0-9]+)[(]*([0-9]*)[)]*/',$tableRaw,$mT);
        $table = $mT[1];
        $id = $mT[2] || null;
        if(empty($id))
        {
            $id = null;
        }

        $fields = explode(' ',$m[2]);
        $columns = array_filter($fields,function($item)
        {
            return !preg_match('/::[0-9]+::/',$item);
        });
        $modifiers = array_filter($fields,function($item)
        {
            return preg_match('/::[0-9]+::/',$item);
        });

        $selectOptions = [
            't' => $table,
            'c' => in_array('*',$columns) ? '*' : implode(',',$columns)
        ];

        if(!is_null($id))
        {
            $selectOptions['w'] = function($item) use($id)
            {
                return $item->id === (int)$id;
            };
        }


        if(count($modifiers))
        {
            $selectOptions['m'] = function($item) use($table,$modifiers)
            {
                foreach($modifiers as $modifier)
                {
                    $modifier = $this->corr[$modifier];
                    preg_match('/([a-zA-Z0-9()]+){([a-zA-Z0-9: *]+)}/',$modifier,$m);
                    $item->{$m[1]} = $this->ploup($modifier,[$table => $item->id]);
                }
            };
        }

        $result = $this->budMod->jds->select($selectOptions);

        $this->api($result);

        // ----------------------------------------------------------

    }

    public function rs($l = 6)
    {
        return substr(str_shuffle(str_repeat('abcdefghijklmnopqrstuvwxyz1234567890',$l)),0,$l);
    }

    public function ploup($query,$w = [])
    {
        preg_match('/([a-zA-Z0-9()]+){([a-zA-Z0-9: *]+)}/',$query,$m);

        $tableRaw = $m[1];
        preg_match('/([a-zA-Z0-9]+)[(]*([0-9]*)[)]*/',$tableRaw,$mT);
        $table = $mT[1];
        $id = $mT[2] || null;
        if(empty($id))
        {
            $id = null;
        }
        $fields = explode(' ',$m[2]);
        $columns = array_filter($fields,function($item)
        {
            return !preg_match('/::[0-9]+::/',$item);
        });
        $modifiers = array_filter($fields,function($item)
        {
            return preg_match('/::[0-9]+::/',$item);
        });

        $selectOptions = [
            't' => $table,
            'c' => implode(',',$columns)
        ];

        if(!empty($w))
        {
            $selectOptions['w'] = function($item) use($w,$id)
            {
                foreach($w as $k => $v)
                {
                    $field = "{$k}Id";
                    if(!is_null($id))
                    {
                        return $item->id === (int)$id && $item->$field === (int)$v;
                    }
                    else
                    {
                        return $item->$field === $v;
                    }
                    break;
                }
            };
        }

        if(count($modifiers))
        {
            $selectOptions['m'] = function($item) use($modifiers)
            {
                foreach($modifiers as $modifier)
                {
                    $modifier = $this->corr[$modifier];
                    preg_match('/([a-z]+){([a-zA-Z0-9: ]+)}/',$modifier,$m);
                    $item->{$m[1]} = $this->ploup($modifier,$item->id);
                }
            };
        }

        return $this->budMod->jds->select($selectOptions);
    }

    public function get_all($table)
    {
        $this->model('allModel','allMod');
        $all = $this->allMod->get_all($table);
        $this->api($all);
    }

    public function get_all_by_id($table,$id)
    {
        $this->model('allModel','allMod');
        $all = $this->allMod->get_all_by_id($table,$id);
        $this->api($all);
    }
    
}