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
                if($this->checkEmail($array_data["user_email"])) {
                    throw new Exception("Email já cadastrado.");
                }
                
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

    public function saveProfile($array_data) {
        $result = false;
        try {
            if(!is_array($array_data)) {
               throw new Exception("Erro de parser (param JSON -> array_data).");
            }

            // Update if receives need_id
            if(isset($array_data["profile_id"])) {
                // Atualizar apenas campos recebidos
                $update_fields = [];
                foreach($array_data as $field => $value) {
                    array_push($update_fields, sprintf("%s='%s'", $field, $value));
                }

                if(empty($update_fields)) {
                    throw new Exception("Nenhum campo a atualizar.");
                }

                // Update data
                $sql = sprintf("UPDATE profile_tbl SET %s WHERE profile_id='%s'", implode(",", $update_fields), $array_data["profile_id"]);

            } else {
                if($this->checkEmail($array_data["user_email"])) {
                    throw new Exception("Email já cadastrado.");
                }
                
                // Insert Data
                $sql = sprintf("
                INSERT INTO
                    profile_tbl
                SET
                    user_id='%s',
                    nickname='%s',
                    state_id='%s',
                    city_id='%s',
                    resume='%s',
                    picture_url='%s'",
                    $array_data["user_id"],
                    $array_data["nickname"],
                    $array_data["state_id"],
                    $array_data["city_id"],
                    $array_data["resume"],
                    $array_data["picture_url"]
                );
            }
            
            $this->query($sql);
            $this->execute();
            
            if(isset($array_data["profile_id"])) {
                // Atualizar dados de um usuario
                $result = (int) $array_data["profile_id"];
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

    public function getProfile($profile_id) {
        $result = false;
        try {
            $query = sprintf("
                SELECT 
                    P.*,
                    S.state_name,
                    C.city_name
                FROM 
                    profile_tbl as P  LEFT JOIN
                    state_tbl as S ON S.state_id = P.state_id LEFT JOIN
                    city_tbl as C USING(city_id)
                WHERE 
                    profile_id='%s'", 
                    $profile_id
            );
            
            $this->query($query);
            $stmt = $this->resultset();
            if(count($stmt) == 0) {                
                throw new Exception(sprintf("Perfil não encontrado: (%s).", $profile_id));
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

    public function removeFlag($game_id, $flag) {
        $result = false;
        try {
            $sql = sprintf("
                DELETE FROM 
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
            $this->execute();
            
            $result = true;
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

    // Check if email is already saved
    public function checkEmail($email) {
        $result = false;
        try {
            $sql = sprintf("SELECT * FROM user_tbl WHERE user_email='%s'", $email);
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