<?php
require_once 'include/DB_Functions.php';
$db = new DB_Functions();
 
// json response array
$response = array("error" => FALSE);
 
if (isset($_POST['email'])){
 
    // receiving the post params
    $email = $_POST['email'];
	
	$user = $db->deleteUser($email);
	
	if ($user != false) {
        // use is found
        $response["error"] = FALSE;
		echo json_encode($response);
    } else {
            // user failed to store
            $response["error"] = TRUE;
            $response["error_msg"] = "Unknown error occurred while deleting user!";
            echo json_encode($response);
    }
		
} else {
    $response["error"] = TRUE;
    $response["error_msg"] = "Email is missing!";
    echo json_encode($response);
}
?>