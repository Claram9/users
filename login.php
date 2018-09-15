<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// array de respuesta json
$response = array("error" => FALSE);
 
if (isset($_POST['email']) && isset($_POST['password'])) {
 
    // almacenamiento de los parámetro
    $email = $_POST['email'];
    $password = $_POST['password'];
 
    // obtener usuario mediante email y contraseña
    $user = $db->getUserByEmailAndPassword($email, $password);
 
    if ($user != false) {
        // usuario encontrado
        $response["error"] = FALSE;
        $response["user"]["name"] = $user["name"];
        $response["user"]["email"] = $user["email"];
		$response["user"]["gender"] = $user["gender"];
		$response["user"]["age"] = $user["age"];
		$response["user"]["marital_status"] = $user["marital_status"];
		$response["user"]["work_status"] = $user["work_status"];
		$response["user"]["cinema"] = $user["cinema_hobby"];
		$response["user"]["music"] = $user["music_hobby"];
		$response["user"]["photo"] = $user["photo_hobby"];
		$response["user"]["paint"] = $user["paint_hobby"];
		$response["user"]["motor"] = $user["motor_hobby"];
		$response["user"]["poetry"] = $user["poetry_hobby"];
		$response["user"]["theatre"] = $user["theatre_hobby"];
		$response["user"]["sports"] = $user["sports_hobby"];
        echo json_encode($response);
    } else {
        // usuario no encontrado
        $response["error"] = TRUE;
        $response["error_msg"] = "Login credentials are wrong. Please try again!";
        echo json_encode($response);
    }
} else {
    // no se han obtenido parámetros requeridos
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters email or password is missing!";
    echo json_encode($response);
}
?>