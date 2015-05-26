<?php
include_once 'db.class.php';

class User extends Database {
    private $error_message = null;

    public function __construct($user_id){
        parent::__construct();
        $this->user_id = $user_id;
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

    public function saveData($array_data) {
        /*
        $result = false;
        try {
            if(!is_array($array_data)) {
               throw new Exception("Erro de parser (param JSON -> array_data).");
            }

            $user_group_id = "0";
            if(isset($array_data["user_group_id"])) {
                $user_group_id = $array_data["user_group_id"];
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
                    user_name='%s',
                    user_phone='%s',
                    user_cpf='%s',
                    user_group_id='%s',
                    user_gcm_id='%s',
                    user_date_register=NOW()",
                    $array_data["user_email"],
                    $array_data["user_password"],
                    $array_data["user_name"],
                    $array_data["user_phone"],
                    $array_data["user_cpf"],
                    $user_group_id,
                    $array_data["user_gcm_id"]
                );   
            }

            // Starts transaction
            $this->beginTransaction();
            
            $this->query($sql);
            $this->execute();
            
            if(isset($array_data["user_id"])) {
                // Atualizar dados de um usuario
                $result = (int) $array_data["user_id"];
            } else {
                // cadastrar um usuario
                $result = $this->lastInsertId();
                // Lança um voucher de 30 dias 
                $this->addVoucher($result, 30);
                // Lança a categoria usuário (para testes)
                $this->addCateg($result, 0);
                // Adiciona o usuário em Porto Alegre (para testes)
                $this->adActCity($result, 4174);
            }

            // Se recebe um user_gcm_id
            if(isset($array_data["user_gcm_id"]) && !empty($array_data["user_gcm_id"])) {
                // Salva na tabela de gcm
                $save_gcm = $this->saveGCMregister($result, $array_data["user_gcm_id"]);
            }

            // Ends transaction
            $this->endTransaction();

        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;
        */
    }

    public function getError() {
        return $this->error_message;
    }
}