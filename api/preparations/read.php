<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/preparations.php';

    // instantiate database and preparations object
    $database = new Database();
    $db = $database->getConnection();

    // call Constructor method of Ingredients and pass db to it and get connection as a response
    $preparation = new Preparations($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query Preparations
    if (isset($_GET['id'])) {
        // getting id from the parameters of URL
        $id = $_GET['id'];
        //print_r($id);
        $response = $preparation->getPreparationsByID($id);
        //print_r($response);
        if ($response!=null) {
           sendResponse(true,"Preparations data are found for the given id.",200,$response);
        } else {
           sendResponse(false,"Preparations data are not found for the given id.",404, $response);
        }
    } else {
        $response = $preparation->getAllPreparationData();
        //print_r($response);
        if ($response!=null) {
            sendResponse(true,"Preparations data are found.",200,$response);
        } else {
            sendResponse(false,"Preparations data are not found.",404, $response);
        }
    }
}

// Error Message
function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
{
    $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode,"data" => $data);
    echo json_encode($response);
}