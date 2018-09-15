<?php
 
class DB_Functions {
 
    private $conn;
 
    // constructor
    function __construct() {
        require_once 'DB_Connect.php';
        // conectar a la base de datos
        $db = new Db_Connect();
        $this->conn = $db->connect();
    }
 
    /**
     * Almacenar nuevo usuario
     * devolver detalles del usuario
     */
    public function storeUser($name, $email, $password, $gender, $age, $marital_status, $work, $cinema, $music, $photo, $paint, $motor, $poetry, $theatre, $sports) {
        $hash = $this->hashSSHA($password);
        $encrypted_password = $hash["encrypted"]; // contraseña encriptada
        $salt = $hash["salt"]; // salt
		
        $stmt = $this->conn->prepare("INSERT INTO user_data(name, email, gender, age, marital_status, work_status, encrypted_password, 
							salt, cinema_hobby, music_hobby, photo_hobby, paint_hobby, motor_hobby, poetry_hobby, theatre_hobby, sports_hobby)
							VALUES(?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssssssiiiiiiii", $name, $email, $gender, $age, $marital_status, $work, $encrypted_password, $salt, $cinema, 
												$music, $photo, $paint, $motor, $poetry, $theatre, $sports);
        $result = $stmt->execute();
        $stmt->close();
		
        // comprobar que se ha añadido correctamente
        if ($result) {
            $stmt = $this->conn->prepare("SELECT * FROM user_data WHERE email = ?");
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
 
            return $user;
        } else {
            return false;
        }
    }
 
    /**
     * Obtener usuario mediante email y contraseña
     */
    public function getUserByEmailAndPassword($email, $password) {
 
        $stmt = $this->conn->prepare("SELECT * FROM user_data WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        if ($stmt->execute()) {
            $user = $stmt->get_result()->fetch_assoc();
            $stmt->close();
            // verificando contraseña
            $salt = $user['salt'];
            $encrypted_password = $user['encrypted_password'];
            $hash = $this->checkhashSSHA($salt, $password);
            // comprobar que son iguales
            if ($encrypted_password == $hash) {
                // los datos del usuario son correctos
                return $user;
            }
        } else {
            return NULL;
        }
    }
 
    /**
     * Comprobar si existe el usuario
     */
    public function isUserExisted($email) {
        $stmt = $this->conn->prepare("SELECT email FROM user_data WHERE email = ?");
 
        $stmt->bind_param("s", $email);
 
        $stmt->execute();
 
        $stmt->store_result();
 
        if ($stmt->num_rows > 0) {
            // exixte
            $stmt->close();
            return true;
        } else {
            // no existe
            $stmt->close();
            return false;
        }
    }
	
	/**
	 * Eliminar usuari de la tabla
	 */
	public function deleteUser($email){
		$stmt = $this->conn->prepare("DELETE FROM user_data WHERE email = ?");
 
        $stmt->bind_param("s", $email);
		
		$result = $stmt->execute();
        $stmt->close();
 
        // comprobar que se ha eliminado correctamente
        if ($result) { 
            return true;
        } else {
            return false;
        }
	}
 
    /**
     * Encriptar contraseña
     * @param password
     * devuelve el salt y la contraseña encriptada
     */
    public function hashSSHA($password) {
 
        $salt = sha1(rand());
        $salt = substr($salt, 0, 10);
        $encrypted = base64_encode(sha1($password . $salt, true) . $salt);
        $hash = array("salt" => $salt, "encrypted" => $encrypted);
        return $hash;
    }
 
    /**
     * Desencriptar contraseña
     * @param salt, password
     * devuelve la cadena hash
     */
    public function checkhashSSHA($salt, $password) {
 
        $hash = base64_encode(sha1($password . $salt, true) . $salt);
 
        return $hash;
    }
	
	/** 
	 * Cambiar hobbies a enteros (1 y 0)
	 */
	public function changeHobby ($hobby){
		switch ($hobby){
			case "Si":
				return 1; 
				break;
			case "No":
				return 0;
				break;
		}
	}
	
	public function changeHobbyInv ($hobby){
		switch ($hobby){
			case 1:
				return "Si"; 
				break;
			case 0:
				return "No";
				break;
		}
	}
}
 
?>