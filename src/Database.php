<?php

namespace Antoineg\Omniscient\Core;

use \PDO;
use Antoineg\Omniscient\Core\JDS;
use Antoineg\Omniscient\Core\Exceptions\DatabaseConnectionException;
use \Exception;

class Database
{
    
    private $omniscient;
    private $dsn;
    private $type;
    private $connection;
    private $host;
    private $name;
    private $user;
    private $pass;
    private $dbname;

    private function generate_connection()
    {
        $connection = null;
        switch($this->type)
        {
            case 'mysql':
                try
                {
                    if(is_null($this->dsn))
                    {
                        $this->dsn = "mysql:dbname={$this->dbname};host={$this->host}";
                    }
                    $connection = new PDO($this->dsn,$this->user,$this->pass);
                }
                catch(Exception $e)
                {
                    throw new DatabaseConnectionException($this->type,$this->dsn);
                }
                break;
            
            case 'jds':
                $connection = new JDS();
                break;
            // case 'pgsql':
            //     try
            //     {
            //         if(is_null($this->dsn))
            //         {
            //             $this->dsn = "pgsql:host={$this->host};dbname={$this->dbname};user={$this->user};password={$this->pass}";
            //         }
            //         $connection = new PDO($this->dsn,$this->user,$this->pass);
            //     }
            //     catch(Exception $e)
            //     {
            //         throw new DatabaseConnectionException($this->type,$this->dsn);
            //     }
            //     break;
        }
        $this->connection = $connection;
    }
    
    public function __construct($omniscient,$type,$dsn = null)
    {
        $this->omniscient = $omniscient;
        $this->type = $type;
        $this->dsn = $dsn;
    }

    public function user($userName)
    {
        $this->user = $userName;
        return $this;
    }

    public function pass($pass)
    {
        $this->pass = $pass;
        return $this;
    }

    public function host($host)
    {
        $this->host = $host;
        return $this;
    }

    public function dbname($dbname)
    {
        $this->dbname = $dbname;
        return $this;
    }

    public function name($connectionName)
    {
        $this->name = $connectionName;
        return $this;
    }

    public function __destruct()
    {
        $this->generate_connection();
        $this->omniscient->singletons->set_singleton($this->name,$this->connection);
    }

}