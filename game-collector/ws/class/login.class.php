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
                    U.*,
                    P.profile_id,
                    P.nickname,
                    P.state_id,
                    P.city_id,
                    P.resume,
                    P.picture_url,
                    S.state_name,
                    C.city_name
                FROM
                    user_tbl as U LEFT JOIN
                    profile_tbl as P USING(user_id) LEFT JOIN
                    state_tbl as S ON S.state_id = P.state_id LEFT JOIN
                    city_tbl as C USING(city_id)
                WHERE 
                    U.user_email='%s' AND
                    U.user_password='%s'",
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