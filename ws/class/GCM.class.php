<?php
//ini_set('display_errors',1);
//ini_set('display_startup_errors',1);
//error_reporting(1);    

class GCM {
    private $cgm_url = 'https://android.googleapis.com/gcm/send';
    private $error_message = null;
    private $api_key = null;
    private $headers = null;
    private $fields = array();

    public function __construct($api_key) {
        $this->api_key = $api_key;
        $this->_setheaders();
        $this->fields['data'] = array(); // seta o campo de dados
        $this->fields['data']["title"] = "BeeCo App"; // message title
    }

    private function _setheaders() {
        $result = false;
        try {
            $this->headers = array(
                sprintf("Authorization: key=%s", $this->api_key),
                'Content-Type: application/json'
            );
        } catch(Exception $e){
            $this->error_message = sprintf("%s : %s", __FUNCTION__, $e->getMessage() );
        }
        return $result;        
    }

    public function setTitle($title) {
        $this->fields['data']["title"] = $title; // message title
    }

    public function setRegistrationIds($registration_ids) {
        $result = false;
        try {
            if(!is_array($registration_ids)) {
                $registration_ids = [$registration_ids];
            }
            $this->fields['registration_ids'] = $registration_ids;
        } catch(Exception $e){
            $this->error_message = sprintf("%s : %s", __FUNCTION__, $e->getMessage() );
        }
        return $result;
    }

    // Adiciona mais informacoes aos dados
    public function adCustomData($custom_data) {
        $result = false;
        try {
            if(!is_array($custom_data)) {
                throw new Exception("custom_data precisa ser um array.");
            }
            
            $this->fields['data'] = array_merge($this->fields['data'], $custom_data);

            $result = $this->fields['data'];
        } catch(Exception $e){
            $this->error_message = sprintf("%s : %s", __FUNCTION__, $e->getMessage() );
        }
        return $result;
    }

    public function sendMessage($message, $json=true) {
        $result = false;
        
        try {
            $this->fields['data']["message"] = $message;
            // Abre a conexao
            $ch = curl_init();
            
            // Seta os dados de postagem
            curl_setopt( $ch, CURLOPT_URL, $this->cgm_url );
            curl_setopt( $ch, CURLOPT_POST, true );
            curl_setopt( $ch, CURLOPT_HTTPHEADER, $this->headers );
            curl_setopt( $ch, CURLOPT_RETURNTRANSFER, true );
            curl_setopt( $ch, CURLOPT_SSL_VERIFYPEER, false );
            curl_setopt( $ch, CURLOPT_POSTFIELDS, json_encode($this->fields) );

            $result = curl_exec($ch);
            
            // Possibilita retornar  um objeto php
            if(!$json and $result) {
                $result = json_decode($result, true);
            }

            curl_close($ch);
        } catch(Exception $e){
            $this->error_message = sprintf("%s : %s", __FUNCTION__, $e->getMessage() );
        }
        
        return $result;
    }

    public function getHeaders() {
        return $this->headers;
    }


    public function getFields() {
        return $this->fields;
    }


    public function getError() {
        return $this->error_message;
    }
}

?>