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
        if($response[0] == null) {
            sendResponse(false,"user does not exist in database.",404,$response[0]);
        } else {
            sendResponse(true,"response found for the given email in database.",200,$response[0]);
        }
    } else {
        //echo "all user data";
        $response = $user->getAllUsersData();

        //print_r($response);
        if($response == null) {
            sendResponse(false,"no any user data are found.",404,$response);
        } else {
            sendResponse(true,"users data are found.",200,$response);
        }
    }
}

// Error Message
function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
{
    $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode,"data" => $data);
    echo json_encode($response);
}

