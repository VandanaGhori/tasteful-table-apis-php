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

// Post Query for user registration
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST['name']) || !isset($_POST['email']) || !isset($_POST['password'])) {
        sendResponse(false,"parameter(s) are missing.",422,null);
    } else {
        // if user already exist by email id.
        $response = $user->getUsersByEmail($_POST['email']);

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
                sendResponse(true,"User is successfully registered.",200,$response[0]);
            }
        } else {
            // user is already exist
            sendResponse(false,"User is already exist.",409,null);
        }
    }
}

// Query Users
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $response = null;
    if (isset($_GET['email'])) {
        //echo "get user by email";
        // getting email from the parameters of URL
        $email = $_GET['email'];
        //print_r($email);
        $response = $user->getUsersByEmail($email);

        //print_r($response);

        sendResponse(true,"response found for the given email",200,$response[0]);
    } else {
        //echo "all user data";
        $response = $user->getAllUsersData();
        sendResponse(true,"response found for all user",200,$response);
    }
}

// Error Message
function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
{
    $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode,"data" => $data);
    echo json_encode($response);
}

