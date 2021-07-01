<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';

// instantiate database and preparations object
$database = new Database();
$db = $database->getConnection();

// call Constructor method of Ingredients and pass db to it and get connection as a response
$user = new Users($db);

// Post Query for user login
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if(!isset($_POST['email']) || !isset($_POST['password'])) {
        sendResponse(false,"parameter(s) are missing.",422,null);
    } else {
        // if user already registered.
        $response = $user->getUsersByEmail($_POST['email']);

        // response is not null means, user is already registered with us.
        if($response[0] != null) {
            $user_data['email'] = $_POST['email'];
            $user_data['password'] = $_POST['password'];

            $result = $user->checkUserLogin($user_data);

            if($result != null) {
                sendResponse(true,"User is successfully logged in.",200,$result[0]);
            } else {
                sendResponse(false,"Wrong credentials are provided.",404,$result);
            }

        } else {
            sendResponse(false,"User is not registered with us. Need to register with us.",401,null);
        }
    }
}

// Error Message
function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
{
    $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode,"data" => $data);
    echo json_encode($response);
}
