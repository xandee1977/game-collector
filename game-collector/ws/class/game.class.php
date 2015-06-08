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
    
    public function findGames($limit=false, $user_id=false, $search=false, $flag=false, $system=1) {
        $result = false;
        try {
            $sql = "SELECT * FROM game_tbl";
            
            $filters = [];
            // For word search
            if($search) {
                //$sql = sprintf("%s WHERE game_title LIKE '%%%s' OR game_title LIKE '%s%%' OR game_title LIKE '%%%s%%'", $sql, $search, $search, $search);
                $filters[] = sprintf("(game_title LIKE '%%%s' OR game_title LIKE '%s%%' OR game_title LIKE '%%%s%%')", $search, $search, $search);                 
            }
            if($flag) {
                //$sql = sprintf("%s WHERE game_title LIKE '%%%s' OR game_title LIKE '%s%%' OR game_title LIKE '%%%s%%'", $sql, $search, $search, $search);
                $filters[] = sprintf("(game_id IN(SELECT game_id FROM user_game_flag_tbl WHERE flag IN('%s') AND user_id=%s))", $flag, $user_id);
            }

            // Seleciona o console (1 = SNES)
            $filtesr[] = sprintf("system_id=%s", $system);

            if(count($filters) > 0) {
                $sql = sprintf("%s WHERE %s", $sql, implode(" AND ", $filters));
            }

            if(!$limit) {
                $limit = "0,20";
            }
            $sql = sprintf("%s LIMIT %s", $sql, $limit);

            $this->query($sql);            
            $stmt = $this->resultset();
            
            if(count($stmt) == 0) {                
                throw new Exception("Nenhum item na lista.");
            }
            foreach($stmt as $key => $item) {
                $stmt[$key]["flags"] = $this->getFlags($item["game_id"], $user_id);
            }

            $result = $stmt;
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }        
        return $result;
    }
   
    public function getGame($game_id) {
        $result = false;
        try {
            $sql = sprintf("SELECT * FROM game_tbl WHERE game_id='%s'", $game_id);
            $this->query($sql);            
            $stmt = $this->resultset();
            
            if(count($stmt) == 0) {                
                throw new Exception("Game nao encontrado.");
            }
            $result = $stmt[0];
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }        
        return $result;
    }

    public function getFlags($game_id, $user_id=false) {
        $result = array("watch" => "N", "have" => "N", "favorite" => "N");

        try {
            if(!$user_id) {
                throw new Exception("usuario nao setado.");
            }
            $sql = sprintf("SELECT * FROM user_game_flag_tbl WHERE user_id='%s' AND game_id='%s'", $user_id, $game_id);
            $this->query($sql);            
            $stmt = $this->resultset();
            if(count($stmt) == 0) {                
                throw new Exception("Nenhum item na lista de flags.");
            }

            foreach($stmt as $item) {
                $result[$item["flag"]] = "Y";
            }

        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }        
        return $result;
    }

    public function getError() {
        return $this->error_message;
    }
}