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
    
    public function getError() {
        return $this->error_message;
    }
}