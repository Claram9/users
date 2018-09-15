<?php
class DB_Connect {
    private $conn;
 
    // Conectar a la base de datos
    public function connect() {
        require_once 'include/Config.php';
         
        // conectando a la base de datos mysql 
        $this->conn = new mysqli(DB_HOST, DB_USER, DB_PASSWORD, DB_DATABASE);
         
        // devolver el manejador de la conexión
        return $this->conn;
    }
}
 
?>