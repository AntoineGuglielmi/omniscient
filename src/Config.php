<?php

namespace Antoineg\Omniscient\Core;

class Config
{

    private function set_paths_constants()
    {
        define('DS',DIRECTORY_SEPARATOR);
        define('ROOT',$this->omniscient->server->document_root.DS);
        define('SRC_PATH',ROOT.'src'.DS);
        define('APP_PATH',ROOT.'app'.DS);

        if(!defined('VIEWS_PATH'))
        {
            define('VIEWS_PATH',APP_PATH.'Views'.DS);
        }
        if(!defined('CONTROLLERS_PATH'))
        {
            define('CONTROLLERS_PATH',APP_PATH.'Controllers'.DS);
        }
        
        if(!defined('DEFAULT_URI'))
        {
            define('DEFAULT_URI','/');
        }
    }

    private function set_namespaces_constants()
    {
        if(!defined('BASE_NAMESPACE'))
        {
            define('BASE_NAMESPACE','Antoineg\\Omniscient\\');
        }
        if(!defined('CLASSES_NAMESPACE'))
        {
            define('CLASSES_NAMESPACE','Antoineg\\Omniscient\\App\\Classes\\');
        }
    }

    private function get_app_config()
    {
        $configDirContent = clean_scandir(scandir(APP_PATH . 'Config'),'Servers.php','Connections.php');
        foreach($configDirContent as $config)
        {
            require APP_PATH . 'Config' . DS . $config;
        }
    }

    private function set_environment()
    {
        $serverName = $this->omniscient->server->server_name;
        $servers = require APP_PATH . 'Config' . DS . 'Servers.php';
        foreach($servers as $env => $envServers)
        {
            foreach($envServers as $server)
            {
                if(preg_match("#^$server$#",$serverName))
                {
                    if(!defined('ENVIRONMENT'))
                    {
                        define('ENVIRONMENT',$env);
                    }
                    break 2;
                }
            }
        }
        if(!defined('ENVIRONMENT'))
        {
            define('ENVIRONMENT','development');
        }
    }

    public function set_misc_constants()
    {
        if(!defined('AUTO_GEN_CONTROLLER'))
        {
            define('AUTO_GEN_CONTROLLER',false);
        }
        if(!defined('AUTO_GEN_VIEW'))
        {
            define('AUTO_GEN_VIEW',false);
        }
        if(!defined('AUTO_GEN_MIDDLEWARE'))
        {
            define('AUTO_GEN_MIDDLEWARE',false);
        }
        if(!defined('AUTO_GEN_LAYOUT'))
        {
            define('AUTO_GEN_LAYOUT',false);
        }
        if(!defined('AUTO_GEN_MODEL'))
        {
            define('AUTO_GEN_MODEL',false);
        }
    }

    private function set_errors()
    {
        if(ENVIRONMENT === 'development')
        {
            ini_set('display_errors', 1);
            ini_set('display_startup_errors', 1);
            error_reporting(E_ALL);
        }
    }

    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
        $this->set_paths_constants();
        $this->set_namespaces_constants();
        $this->get_app_config();
        $this->set_misc_constants();
        $this->set_environment();
        $this->set_errors();
    }

}