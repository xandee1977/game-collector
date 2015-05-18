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
    
    public function game_list($limit='0,100') {
        $result = false;
        try {
            $query = sprintf("SELECT * FROM game_tbl ORDER BY game_title ASC LIMIT %s", $limit);
            $this->query($query);
            $stmt = $this->resultset();
            if(count($stmt) == 0) {                
                throw new Exception("Game nao encontrado.");
            }
            $result = $stmt;
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;        
    }

    public function search($title) {
        $result = false;
        try {
            $query = sprintf("SELECT * FROM game_tbl WHERE game_title LIKE '%s' OR game_title LIKE '%%%s' OR game_title LIKE '%s%%'", $title, $title, $title);
            $this->query($query);
            $stmt = $this->resultset();
            if(count($stmt) == 0) {                
                throw new Exception("Game nao encontrado.");
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