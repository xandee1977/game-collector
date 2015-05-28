<?php
include_once 'db.class.php';

class Login extends Database {
    private $error_message = null;

    // Check if game are fpagued yet
    public function doLogin($user_email, $user_pass) {
        $result = false;
        try {
            $sql = sprintf("
                SELECT 
                    *
                FROM
                    user_tbl 
                WHERE 
                    user_email='%s' AND
                    user_password='%s'",
                    $user_email,
                    $user_pass
            );

            $this->query($sql);
            $stmt = $this->resultset();
            if(count($stmt) == 0) {
                throw new Exception("Verifique usuário e senha.");
            }
            $result = $stmt[0];
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;
    }

    public function getError() {
        return $this->error_message;
    }
}