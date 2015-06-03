<?php
include_once 'db.class.php';

class Address extends Database {
    private $error_message = null;

    public function __construct(){
        parent::__construct();
    }

    public function listState() {
        $result = false;
        try {
            $query = "SELECT * FROM state_tbl ORDER BY state_name ASC";
            $this->query($query);
            $stmt = $this->resultset();
            if(count($stmt) == 0) {                
                throw new Exception("Erro ao listar estados.");
            }
            $result = $stmt;
        } catch(Exception $e){
            $this->error_message = $e->getMessage();
        }
        return $result;
    }
    
    public function listCity($state_id, $search=null) {
        $result = false;
        try {
            $sql_main = "SELECT * FROM city_tbl";
            
            // TODO: adding filters here
            $sql_filter = sprintf("state_id='%s'", $state_id);
            if(!is_null($search)) {
                $sql_filter = sprintf("%s AND ((city_name LIKE '%%%s') OR (city_name LIKE '%s%%') OR (city_name LIKE '%%%s%%'))", $sql_filter,$search, $search, $search);
            }            
            $sql_order =" ORDER BY city_name ASC";
            $query = sprintf("%s WHERE %s%s", $sql_main, $sql_filter, $sql_order);

            $this->query($query);
            $stmt = $this->resultset();
            if(count($stmt) == 0) {                
                throw new Exception("Erro ao listar cidades.");
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