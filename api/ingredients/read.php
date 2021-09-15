<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/ingredients.php';

    // instantiate database and ingredients object
    $database = new Database();
    $db = $database->getConnection();

    // call Constructor method of Ingredients and pass db to it and get connection as a response
    $ingredients = new Ingredients($db);

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    // Query Ingredients
    if (isset($_GET['id'])) {
        // getting id from the parameters of URL
        $id = $_GET['id'];
        //print_r($id);
        $response = $ingredients->getIngredientsByID($id);
        //print_r($response);
        if($response != null) {
            //print_r($response);
            sendResponse(true,"All ingredients are fetched for the given id.",200,$response);
        } else {
            sendResponse(false,"Ingredients data are not found for the given id.",404,$response);
        }
    } else {
        $response = $ingredients->getAllIngredients();
        //print_r($response);
        if($response != null) {
            //print_r($response);
            sendResponse(true,"All ingredients data are fetched.",200,$response);
        } else {
            sendResponse(false,"Data not found for ingredients.",404,$response);
        }
    }
}

// Error Message
function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
{
    $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode,"data" => $data);
    echo json_encode($response);
}