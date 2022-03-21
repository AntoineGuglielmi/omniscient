<?php

namespace Antoineg\Omniscient\Core\Traits;

use \PDO;
use Antoineg\Omniscient\Core\Exceptions\ConnectionException;

Trait ModelTrait
{

    private $omniscient;
    private $connection;
//     private $connectionType;
//     private $databaseName;

//     private function connect()
//     {
//         try
//         {
//             switch($this->connectionType)
//             {
//                 case 'mysql':
//                     $this->connexion = new PDO("mysql:host=localhost;dbname=testdb");

//             }
//         }
//         catch(Exception $e)
//         {
//             $message = $e->getMessage();
//             echo "<p class=\"warning\">$message</p>";
//         }
//     }

    public function __get($connectionName)
    {
        if(!isset($this->omniscient->singletons->singletons[$connectionName]))
        {
            throw new ConnectionException($connectionName,__CLASS__);
        }
        // try
        // {
            return $this->connection = $this->omniscient->singletons->get_singleton($connectionName);
            return $this;
        // }
        // catch(\Exception $e)
        // {
        // }
        // $connection = $this->omniscient->singletons[$connectionName];
        // if(is_null($connection))
        // {
            
        // }
        // return $this->omniscient->singletons->get_singleton($connectionName);
    }

    public function __construct($omniscient)
    {
        $this->omniscient = $omniscient;
    }
    
    public function query($u_params)
    {
        // if(!$this->_dbSet)
        // {
        //     $this->set_db();
        // }
        
        $d_params = [
            'binds' => [],
            'class' => 'stdClass',
            'fetch' => '*'
        ];

        $params = array_replace_recursive($d_params,$u_params);

        $this->_stmt = $this->connection->prepare($params['query']);
        $this->_stmt->execute($params['binds']);

		if($params['class'] !== 'stdClass'){
			$params['class'] = CLASSES_NAMESPACE . $params['class'];
        }

        $this->_stmt->setFetchMode(PDO::FETCH_CLASS,$params['class']);

        if($params['fetch'] !== 0)
        {
            switch($params['fetch'])
            {
                case 1:
                    $this->_stmt = $this->_stmt->fetch();
                break;

                case '*':
                    $this->_stmt = $this->_stmt->fetchAll();
                break;
            }
            return $this->_stmt;
        }

        $this->_stmt->closeCursor();
        $this->_stmt = NULL;
    }

}