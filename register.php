<?php
 
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// array de respuesta json
$response = array("error" => FALSE);
 
if (isset($_POST['name']) && isset($_POST['email']) && isset($_POST['password']) && isset($_POST['gender']) && isset($_POST['age'])
	 && isset($_POST['marital_status']) && isset($_POST['work_status']) && isset($_POST['cinema']) && isset($_POST['music']) && isset($_POST['photography'])
	 && isset($_POST['painting']) && isset($_POST['motor']) && isset($_POST['poetry']) && isset($_POST['theatre']) && isset($_POST['sports'])) {
 
    // almacenando parámetros post
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
	$gender = $_POST['gender'];
	$age = $_POST['age'];
	$marital_status = $_POST['marital_status'];
	$work = $_POST['work_status'];
	$cinema = $_POST['cinema'];
	$music = $_POST['music'];
	$photo = $_POST['photography'];
	$paint = $_POST['painting'];
	$motor = $_POST['motor'];
	$poetry = $_POST['poetry'];
	$theatre = $_POST['theatre'];
	$sports = $_POST['sports'];
 
    // comprobar si ya existe un usuario con ese email
    if ($db->isUserExisted($email)) {
        // usuario existente
        $response["error"] = TRUE;
        $response["error_msg"] = "User already existed with " . $email;
        echo json_encode($response);
    } else {
		$cinema = $db->changeHobby($cinema);
		$music = $db->changeHobby($music);
		$photo = $db->changeHobby($photo);
		$paint = $db->changeHobby($paint);
		$motor = $db->changeHobby($motor);
		$poetry = $db->changeHobby($poetry);
		$theatre = $db->changeHobby($theatre);
		$sports = $db->changeHobby($sports);
        // crear nuevo usuario
        $user = $db->storeUser($name, $email, $password, $gender, $age, $marital_status, $work, $cinema, 
								$music, $photo, $paint, $motor, $poetry, $theatre, $sports);
        if ($user) {
            // usuario almacenado correctamente
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
            // fallo al almacenar usuario
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred in registration!";
            echo json_encode($response);
        }
    }
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Required parameters are missing!";
    echo json_encode($response);
}
?>