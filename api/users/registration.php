<?php
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

// include database and object files
include_once '../config/database.php';
include_once '../objects/users.php';
include_once '../objects/sessions.php';

// instantiate database and preparations object
$database = new Database();
$db = $database->getConnection();

// call Constructor method of Ingredients and pass db to it and get connection as a response
$user = new Users($db);

// Post Query for user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        sendResponse(false,"parameter(s) are missing.",422,null);
    } else {
        // if user already exist by email id.
        $response = $user->getUsersByEmail($_POST['email']);

        // response is null means record does not found from the database.
        if($response[0] == null) {
            //echo "you can create new user";
            // if user is new and email id is unique.
            $user_data['name'] = $_POST['name'];
            $user_data['email'] = $_POST['email'];
            $user_data['password'] = $_POST['password'];

            $result = $user->newUserRegistration($user_data);

            if($result) {
                // get recently added user details.
                $response = $user->getUsersByEmail($_POST['email']);

                // For generating a token and store it into a sessions table
                $bytes = openssl_random_pseudo_bytes(16,$cStrong);
                $hex = bin2hex($bytes);
                $token = $hex.$cStrong;
                //print($token);

                // Generate a new token as new user registration is done and store them
                // into database user_session and and return new token with the response
                $userSession = new Sessions($db);
                $userSession->newUserTokenGenerator($response[0]['id'],$token);
                $response[0]['token'] = $token;

                sendResponse(true,"User is successfully registered.",200,$response[0]);
            }
        } else {
            // user is already exist
            sendResponse(false,"User is already exist.",409,null);
        }
    }
}

// Error Message
function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
{
    $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode,"data" => $data);
    echo json_encode($response);
}

