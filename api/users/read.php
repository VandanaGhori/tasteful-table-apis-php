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
        sendErrorResponse("parameter(s) are missing.");
    } else {
        // if user already exist by email id.
        $num = ($user->getUsersByEmail($_POST['email']))->rowCount();
        if ($num > 0) {
            // Set response code - 404 not found.
            http_response_code(404);

            echo sendErrorResponse("User already exist.");

        } else {        // if user is new and email id is unique.
            $user_data = array($_POST['name'], $_POST['email'], $_POST['password']);
            $result = $user->newUserRegistration($user_data);

            print_r($result);
            if ($result) {
                // set response code - 200 OK
                http_response_code(200);
                $response = array("success" => true, "message" => "User registered successfully!");
                echo json_encode($response);
            } else {
                http_response_code(404);
                echo sendErrorResponse("Opps! Something went wrong.");
            }
            // show preparation data in JSON format
            //echo json_encode(array("message" => "User created successfully."));
        }
    }
}

// Query Users
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    if (isset($_GET['email'])) {
        // getting email from the parameters of URL
        $email = $_GET['email'];
        //print_r($email);
        $stmt = $user->getUsersByEmail($email);
    } else {
        $stmt = $user->getAllUsersData();
    }
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if ($num > 0) {
        $preparation_arr = array();

        // pushing the values in key name "data"
        //$preparation_arr["data"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $preparation_item = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "email" => $row['email'],
                "password" => $row['password']
            );

            array_push($preparation_arr, $preparation_item);
        };

        // set response code - 200 OK
        http_response_code(200);

        // show preparation data in JSON format
        echo json_encode($preparation_arr, JSON_UNESCAPED_SLASHES);
    } else {

        // set response code - 404 not found
        http_response_code(404);

        // tell the user no preparations steps are found
        echo json_encode(array("message" => "No Users are Found."));
    }
}

// Error Message
function sendErrorResponse($msg)
{
    $response = array("success" => false, "error" => $msg);
    echo json_encode($response);
}

