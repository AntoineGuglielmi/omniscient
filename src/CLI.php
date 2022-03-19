<?php

namespace Antoineg\Omniscient\Core;

use ReflectionClass;
use ReflectionMethod;

class CLI
{

    private $function;
    private $params;
    private $functions;
    private $aliases = [
        'controller' => ['c','cont'],
        'middleware' => ['midd'],
        'view' => ['v'],
        'model' => ['m','mod'],
        'command' => ['comm'],
        'sass' => ['s']
    ];

    public const SPACES = 2;
    public const ROOT = __DIR__ . '\\..\\';
    public const CLI_DIR = __DIR__ . '\\..\\src\\CLI';
    public const CLI_NAMESPACE = '\\..\\Antoineg\\Omniscient\\Core\\CLI';
    public const CONTROLLERS_DIR = __DIR__ . '\\..\\app\\Controllers';
    public const MIDDLEWARES_DIR = __DIR__ . '\\..\\app\\Middlewares';
    public const MODELS_DIR = __DIR__ . '\\..\\app\\Models';
    public const VIEWS_DIR = __DIR__ . '\\..\\app\\Views\\pages';
    public const LAYOUTS_DIR = __DIR__ . '\\..\\app\\Views\\layouts';

    private function set_functions()
    {
        $functions = clean_scandir(scandir('src/CLI'));
        array_walk($functions,function(&$v)
        {
            $v = str_replace('.php','',$v);
        });
        $this->set('functions',array_values($functions));
    }

    private function set($prop,$value)
    {
        $this->$prop = $value;
    }

    private function get($prop)
    {
        return $this->$prop;
    }

    private function is_function_ok($function)
    {
        return in_array($function,$this->get_functions());
    }

    ////////////////////////////////////////////////////////////////////////////////////////////////////

    public function __construct($args)
    {
        $this->set('function',$args[1]);
        $this->set('params',array_slice($args,2));
        $this->set('styles',[
            'foreColors' => [
                'black' => '0;30',
                'dark_grey' => '1;30',
                'red' => '0;31',
                'light_red' => '1;31',
                'green' => '0;32',
                'light_green' => '1;32',
                'brown' => '0;33',
                'yellow' => '1;33',
                'blue' => '0;34',
                'light_blue' => '1;34',
                'magenta' => '0;35',
                'light_magenta' => '1;35',
                'cyan' => '0;36',
                'light_cyan' => '1;36',
                'light_grey' => '0;37',
                'white' => '1;37'
            ],
            'backColors' => [
                'black' => '40',
                'red' => '41',
                'green' => '42',
                'yellow' => '43',
                'blue' => '44',
                'magenta' => '45',
                'cyan' => '46',
                'light_grey' => '47'
            ],
            'start' => "\e[",
            'end' => 'm',
            'reset' => "\e[0"
        ]);

        $this->set_functions();

        $functionClass = "Antoineg\\Omniscient\\Core\\CLI\\{$this->get('function')}";
        if(class_exists($functionClass))
        {
            $functionClass = new $functionClass($this);
            call_user_func_array([$functionClass,'run'],$this->get('params'));
        }
        else
        {
            foreach($this->get('aliases') as $func => $aliases)
            {
                if(in_array($this->get('function'),$aliases))
                {
                    $functionClass = "Antoineg\\Omniscient\\Core\\CLI\\$func";
                    if(class_exists($functionClass))
                    {
                        $functionClass = new $functionClass($this);
                        call_user_func_array([$functionClass,'run'],$this->get('params'));
                        return;
                    }
                }
            }
            echo "La fonction " . $this->string($this->get('function'),'light_red') . " n'est pas disponible.\n\n";
        }
    }

    public function get_functions()
    {
        return $this->get('functions');
    }

    public function string($string,$fore,$back = null)
    {
        $foreColor = $this->styles['foreColors'][$fore] ?? $this->styles['foreColors']['white'];
        $backColor = isset($this->styles['backColors'][$back]) ? ':'.$this->styles['backColors'][$back] : '';
        $start = $this->styles['start'];
        $end = $this->styles['end'];
        $reset = $this->styles['reset'];
        return "$start$foreColor$backColor$end$string$start$reset$end";
    }

}