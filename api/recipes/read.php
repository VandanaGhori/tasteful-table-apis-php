<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/recipes.php';

    // instantiate database and recipes object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object and pass database to the constructor and return the connection
    $recipe = new Recipes($db);

    if ($_SERVER['REQUEST_METHOD'] === 'GET') {
        // query recipes
        $response = $recipe->getAllRecipe();

        if($response!=null) {
            //print_r($response);
            sendResponse(true,"All Recipes are fetched.",200,$response);
        } else {
            sendResponse(false,"Data not found for recipes.",404,$response);
        }
    }

    // Error Message
    function sendResponse($success = true, $error_msg, $errorCode = 504, $data) {
        $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode,"data" => $data);
        echo json_encode($response);
    }





