<?php

namespace Antoineg\Omniscient\Core;

class JDS
{



    private $jds_directory = null;



    private function gtd($table,$whats)
    {
        $output = [];
        $whats = explode(',',$whats);
        $object = json_decode(file_get_contents($this->jds_directory."/$table.json"));
        foreach($whats as $w)
        {
            $output[] = $object->$w;
        }
        return $output;
    }



    private function majtd($tableName,$whats,...$maj)
    {
        $tableFile = $this->jds_directory."/$tableName.json";
        $tableContent = json_decode(file_get_contents($tableFile));
        $whats = explode(',',$whats);
        for($i = 0; $i < count($whats); $i++)
        {
            $field = $whats[$i];
            $value = $maj[$i];
            $tableContent->$field = $value;
        }
        file_put_contents($this->jds_directory."/$tableName.json",json_encode($tableContent));
    }



    private function params($d_params,$u_params)
    {
        $params = array_replace_recursive($d_params,$u_params);
        return (object) $params;
    }



    private function result($data)
    {
        return [
            'date' => time(),
            'data' => $data
        ];
    }


    
    public function __construct()
    {
        $this->jds_directory = APP_PATH . 'JDS' . DS;
    }


    
    /**
     * Permet de sélectionner tout ou partie des enregistrements d'une table.  
     * Prends en paramètre un tableau :
     * - 'c' : les colonnes de la table à sélectionner, sous la forme d'une chaîne de caractère regroupant les noms des colonnes séparés par une virgule. Par défaut : '*' (tous)
     * - 't' : la table dans laquelle effectuer la sélection, au format 'database.table' ou 'database/table'
     * - 'w' : une fonction permettant de filtrer les enregistrements. La fonction prend en paramètre chaque enregistrement : si la fonction retourne ***true***, l'enregistrement sera sélectionner, sinon il sera ignoré
     * - 'm' : une fonction permettant de modifier l'enregistrement avant sa sélection, par exemple pour ajouter un champ ou une colonne. La fonction prend en paramètre chaque enregistrement
     * - 'o' : un tableau de chaînes de caractères définissant les règles du tri des résultats. Les règles sont au format ***champ***:***direction***, le ***champ*** étant l'une des propriétés des enregistrements et la ***direction*** étant 'asc' ou 'desc'
     * - 'l (L minuscule)' : une chaîne de caractères définissant le nombre limite de résultats, au format ***quantité***:***décalage_compris***. Exemple : '2:3' sélectionnera les 2 premiers résultats à partir du 3ème compris ; '2' sélectionnera les 2 premiers résultats sans décalage
     * - 'i' : une chaîne de caractères définissant la class de l'objet à retourner
     * 
     * @param  [string,string,function,function,string[],string] $u_params Voir description
     */
    public function select($u_params = [])
    {
        $output = [];
        
        $params = $this->params([
            'c' => '*',
            'w' => function(){ return true; },
            'm' => function(){ return; },
            'o' => null,
            'l' => null,
            'i' => 'stdClass'
        ],$u_params);

        if($params->c !== '*')
        {
            $params->c = explode(',',$params->c);
        }

        $table = $params->t;

        list($tableRecords) = $this->gtd($table,'data');

        $output = array_filter($tableRecords,function($item) use($params)
        {
            return call_user_func($params->w,$item);
        });

        if($params->c !== '*')
        {
            $cs = $params->c;
            array_walk($output,function(&$v,$i) use($cs,$params)
            {
                $class = $params->i;
                $newV = new $class();
                foreach($cs as $c)
                {
                    $newV->$c = $v->$c;
                }
                $v = $newV;
            });
        }

        array_walk($output,function(&$v,$i) use($params)
        {
            call_user_func($params->m,$v);
        });

        $orderBys = $params->o;
        if(!is_null($orderBys))
        {
            usort($output,function($a,$b) use($orderBys)
            {
                for($i = 1; $i < count($orderBys); $i++)
                {
                    $previousOrderByRule = $orderBys[$i-1];
                    $previousOrderByRule = explode(':',$previousOrderByRule);
                    $previousOrderByField = $previousOrderByRule[0];
                    $currentOrderByRule = $orderBys[$i];
                    $currentOrderByRule = explode(':',$currentOrderByRule);
                    $currentOrderByField = $currentOrderByRule[0];
                    $orderByDirection = $currentOrderByRule[1];
                    if($a->$previousOrderByField == $b->$previousOrderByField)
                    {
                        switch($orderByDirection)
                        {
                            case 'desc':
                                return $a->$currentOrderByField < $b->$currentOrderByField ? 1 : -1;
                                break;
                            case 'asc':
                                return $a->$currentOrderByField > $b->$currentOrderByField ? 1 : -1;
                                break;
                        }
                    }
                }
                $firstOrderByRule = $orderBys[0];
                $firstOrderByRule = explode(':',$firstOrderByRule);
                $firstOrderByField = $firstOrderByRule[0];
                $orderByDirection = $firstOrderByRule[1];
                switch($orderByDirection)
                {
                    case 'desc':
                        return $a->$firstOrderByField < $b->$firstOrderByField ? 1 : -1;
                        break;
                    case 'asc':
                        return $a->$firstOrderByField > $b->$firstOrderByField ? 1 : -1;
                        break;
                }
            });
        }

        $limit = $params->l;
        if(!is_null($limit))
        {
            $limit = explode(':',$limit);
            $length = $limit[0];
            $offset = $limit[1] ?? 1;
            $output = array_slice($output,$offset-1,$length);
        }

        if($params->i !== 'stdClass')
        {
            include APP_PATH."Classes/$params->i.php";
            array_walk($output,function(&$v,$k) use($params)
            {
                $v = unserialize(sprintf(
                    'O:%d:"%s"%s',
                    strlen($params->i),
                    $params->i,
                    strstr(strstr(serialize($v), '"'), ':')
                ));
            });
        }

        $output = array_values($output);
        return $output;
    }



    /**
     * Insert l'enregistrement $record dans la table $dbDotTable.
     * L'enregistrement DOIT AVOIR un champ 'id'
     *
     * @param  string $dbDotTable La table dans laquelle insérer l'enregistrement, au format 'database.table' ou 'database/table'
     * @param  object $record L'enregistrement à insérer dans la table
     * @return void
     */
    public function insert($table,$object)
    {
        list($data,$liid) = $this->gtd($table,'data,liid');
        $liid++;
        $object->id = $liid;
        $object->created_at = time();
        $data[] = $object;
        $this->majtd($table,'data,liid',$data,$liid);
    }



    /**
     * Permet de supprimer un ou plusieurs enregistrements d'une table.  
     * Prends en paramètre un tableau :
     * - 't' : la table depuis laquelle supprimer l'enregistrement, au format 'database.table' ou 'database/table'
     * - 'w' : une fonction permettant de filtrer les enregistrements. La fonction prend en paramètre chaque enregistrement : si la fonction retourne ***true***, l'enregistrement sera supprimer, sinon il sera ignoré
     *
     * @param  [string,function] $u_params Voir description
     */
    public function delete($u_params)
    {
        $params = $this->params([
            'w' => function(){ return false; }
        ],$u_params);

        $table = $params->t;
        list($before) = $this->gtd($table,'data');
        $after = array_filter($before,function($item) use($params)
        {
            return !call_user_func($params->w,$item);
        });
        $after = array_values($after);
        $this->majtd($table,'data',$after);
    }



    /**
     * Permet de modifier un ou plusieurs enregistrements d'une table.  
     * Prends en paramètre un tableau :
     * - 't' : la table dans laquelle modifier l'enregistrement, au format 'database.table' ou 'database/table'
     * - 'w' : une fonction permettant de filtrer les enregistrements. La fonction prend en paramètre chaque enregistrement : si la fonction retourne ***true***, l'enregistrement sera modifier, sinon il sera ignoré
     * - 's' : modificateur de l'enregistrement. Peut être  un tableau de paires ***champ*** => ***valeur*** ou une fonction prenant en paramètre chaque enregistrement
     *
     * @param  mixed $u_params
     */
    public function update($u_params = [])
    {
        $params = $this->params([
            'w' => function(){ return true; },
            's' => function(){ return; }
        ],$u_params);
        
        $table = $params->t;

        list($tableRecords) = $this->gtd($table,'data');
        array_walk($tableRecords,function(&$item) use($params)
        {
            $sType = gettype($params->s);
            if(call_user_func($params->w,$item))
            {
                switch($sType)
                {
                    case 'object':
                        call_user_func($params->s,$item);
                        break;
                    case 'array':
                        foreach($params->s as $k => $v)
                        {
                            $item->$k = $v;
                        }
                        break;
                }
            }
        });
        $this->majtd($table,'data',$tableRecords);
    }



    function getMaxInTable($table,$field)
    {
        $ordres = select([
            't' => $table,
            'c' => $field
        ]);
        return array_reduce($ordres, function($a, $b){
            return $a ? ($a->ordre > $b->ordre ? $a : $b) : $b;
        })->ordre;
    }



    function getMinInTable($table,$field)
    {
        $ordres = select([
            't' => $table,
            'c' => $field
        ]);
        return array_reduce($ordres, function($a, $b){
            return $a ? ($a->ordre < $b->ordre ? $a : $b) : $b;
        })->ordre;
    }



}