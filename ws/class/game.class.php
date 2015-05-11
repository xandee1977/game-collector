<?php
include_once 'db.class.php';

class Game extends Database {
    private $error_message = null;

    public function __construct(){
        parent::__construct();
    }

    public function save($array_data){
        $result = false;
        try {
            $fields = [];
            foreach($array_data as $field => $value) {
                $fields[] = sprintf("%s='%s'", $field, $value);
            }
            $fields_sql = implode(",", $fields);

            $sql  = sprintf("INSERT INTO game_tbl SET %s", $fields_sql);
            $this->query($sql);
            $this->execute();

            $result = $this->lastInsertId();
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }        
        return $result;
    }
    
    public function findGames($limit=false) {
        $result = false;
        try {
            $sql = "SELECT * FROM game_tbl";
            if(!$limit) {
                $limit = "0,20";
            }
            $sql = sprintf("%s LIMIT %s", $sql, $limit);

            $this->query($sql);            
            $stmt = $this->resultset();
            
            if(count($stmt) == 0) {                
                throw new Exception("Nenhum item na lista.");
            }
            $result = $stmt;
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }        
        return $result;
    }
    
    public function getError() {
        return $this->error_message;
    }
}