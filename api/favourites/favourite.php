<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../objects/favourites.php';

    $database = new Database();
    $db = $database->getConnection();

    $favourites = new Favourites($db);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!isset($_GET['rec_id'])) {
            sendResponse();
        }
    }

    function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
    {
        $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode, "data" => $data);
        return json_encode($response);
    }

