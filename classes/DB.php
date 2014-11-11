<?php

class DB {

    private static $_instance;
    private $_pdo, 
            $_query, 
            $_results, 
            $_error = false, 
            $_count = 0;
    
    private function __construct() {
        try{
          $this->_pdo = new PDO("mysql:host=".Config::get('mysql/host').";dbname=".Config::get('mysql/dbname'), Config::get('mysql/dbuser'), Config::get('mysql/dbpassword'));  
    
          }  catch (PDOException $e){
            die($e->getMessage()); 
        }
    }
    
    public static function getInstance(){
        if(!isset(self::$_instance)){
            self::$_instance = new DB();
        }
        return self::$_instance;
    }
    
    public function query($sql, $params = array()){
        $this->_error = false;
        if($this->_query = $this->_pdo->prepare($sql)){
            $x=1;
            if(count($params)){
                foreach ($params as $param){
                    $this->_query->bindValue($x, $param);
                            $x++;
                }
            }
            if($this->_query->execute()){
                $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
                $this->_count = $this->_query->rowCount();
            } else{
                $this->_error = TRUE;
            }
        }
        return $this;
    }
    
    private function action($action, $table, $where = array()){
        if(count($where)===3){
            $operators = array('=', '>', '<', '>=', '<=');
            
            $field = $where[0];
            $operator = $where[1];
            $value = $where[2];
            
            if(in_array($operator, $operators)){
                $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
                if(!$this->query($sql, array($value))->error()){
                    return $this;
                }
            }
        }
        return FALSE;
    }
    
public function get($table, $where){
    return $this->action('SELECT *', $table, $where);
}

public function insert($table, $fields = array()){
    if(count($fields)){
        $keys = array_keys($fields);
        $values = "";
        $x =1;
        foreach ($fields as $fiels){
            $values .= "?";
            if($x< count($fields)){
                $values .= ", ";
               
            }
            $x++;
          
        }
      
        $sql = "INSERT INTO {$table} (`".  implode('`, `', $keys)."`) VALUES({$values})";
     if(!$this->query($sql, $fields)->error()){
         return TRUE;
     }
    }
return FALSE;
}

public function update($table, $id, $fields = array()){
 if(count($fields)){
        $set = '';
        $x = 1;
        
        foreach($fields as $name => $value){
            $set .= "{$name} = ?";
            if($x < count($fields)){
                $set .= ', ';
            }
            $x++;
            
            
        }
        $sql = "UPDATE {$table} SET {$set} WHERE id = $id";
      
            if(!$this->query($sql, $fields)->error()){
         return TRUE;
     }   
 }

    return FALSE;
}

public function delet($table, $where){
     return $this->action('DELETE', $table, $where);
}

public function results(){
    return $this->_results;
}

public function firstResult(){
    return $this->_results[0];
}

public function error(){
        return $this->_error;
    }
    
    public function count(){
        return $this->_count;
    }

}
