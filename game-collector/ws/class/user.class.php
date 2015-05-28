<?php
include_once 'db.class.php';

class User extends Database {
    private $error_message = null;

    public function __construct($user_id=null){
        parent::__construct();
        $this->user_id = $user_id;
    }

    public function saveData($array_data) {
        $result = false;
        try {
            if(!is_array($array_data)) {
               throw new Exception("Erro de parser (param JSON -> array_data).");
            }

            // Update if receives need_id
            if(isset($array_data["user_id"])) {
                // Atualizar apenas campos recebidos
                $update_fields = [];
                foreach($array_data as $field => $value) {
                    array_push($update_fields, sprintf("%s='%s'", $field, $value));
                }

                if(empty($update_fields)) {
                    throw new Exception("Nenhum campo a atualizar.");    
                }

                // Update data
                $sql = sprintf("UPDATE user_tbl SET %s WHERE user_id='%s'", implode(",", $update_fields), $array_data["user_id"]);

            } else {
                // Insert Data
                $sql = sprintf("
                INSERT INTO
                    user_tbl
                SET
                    user_email='%s',
                    user_password=MD5('%s'),
                    user_date_register=NOW()",
                    $array_data["user_email"],
                    $array_data["user_password"]
                );
            }
            
            $this->query($sql);
            $this->execute();
            
            if(isset($array_data["user_id"])) {
                // Atualizar dados de um usuario
                $result = (int) $array_data["user_id"];
            } else {
                // cadastrar um usuario
                $result = $this->lastInsertId();
            }

        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;
    }

    public function getData() {
        $result = false;
        try {
            $query = sprintf("SELECT * FROM user_tbl where user_id='%s'", $this->user_id);
            $this->query($query);
            $stmt = $this->resultset();
            if(count($stmt) == 0) {                
                throw new Exception(sprintf("Usuário não encontrado: (%s).", $this->user_id));
            }
            $result = $stmt[0];
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;
    }

    // Mark as watch
    public function flagWatch($game_id) {
        $flag = "watch";
        return $this->addFlag($game_id, $flag);        
    }

    // Mark as favorite
    public function flagFavorite($game_id) {
        $flag = "favorite";
        return $this->addFlag($game_id, $flag);        
    }

    // // Mark as have
    public function flagHave($game_id) {
        $flag = "have";
        return $this->addFlag($game_id, $flag);
    }    

    public function addFlag($game_id, $flag) {
        $result = false;
        try {
           // Testa o id de usuario
            if(is_null($this->user_id)) {
                throw new Exception("user_id nulo!.");
            }

            // O Game ja foi tem esta flag para o usuário
            if($this->checkFlag($game_id, $flag)) {
                throw new Exception("Flag ja existente.");
            }

            $sql = sprintf("
                INSERT INTO 
                    user_game_flag_tbl 
                SET 
                    user_id='%s',
                    game_id='%s',
                    flag='%s',
                    flag_date=NOW()",
                    $this->user_id,
                    $game_id,
                    $flag
            );
            
            $this->query($sql);
            $this->execute();
            $result = $this->lastInsertId();
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;
    }

    // Check if game are fpagued yet
    public function checkFlag($game_id, $flag) {
        $result = false;
        try {
            // Testa o id de usuario
            if(is_null($this->user_id)) {
                throw new Exception("user_id nulo!.");
            }

            $sql = sprintf("
                SELECT 
                    flag_id
                FROM
                    user_game_flag_tbl 
                WHERE 
                    user_id='%s' AND
                    game_id='%s' AND
                    flag='%s'",
                    $this->user_id,
                    $game_id,
                    $flag
            );

            $this->query($sql);
            $stmt = $this->resultset();
            if(count($stmt) > 0) {                
                $result = true;
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