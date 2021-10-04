<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../objects/favourites.php';

    $database = new Database();
    $db = $database->getConnection();

    $favourites = new Favourites($db);

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        $database = new Database();
        $db = $database->getConnection();

        $user_session = new Sessions($db);

        // retrieve token from header
        $headers = getallheaders();
        $token = $headers["Token"];

        print_r("Token-".$token);

        //$user_id = $user_session->getUserIdFromToken($token);
        $user_id = $user_session->getUserIdFromToken("e3d08d7c12461ab8b0a27c7cf7d777921");

        if ($user_id != 0 || $user_id != null) {
            //print_r("Hello before user found");
            $response = $favourites->getAllFavouriteRecipes($user_id);
            //print_r("Hello after user found");

            if ($response != null) {
                //print_r($response);
                sendResponse(true, "All Favourites of users are fetched.", 200, $response);
            } else {
                sendResponse(false, "User has no any favourite recipe", 404, $response);
            }
        } else {
            sendResponse(false, "User is not authorized.", 404, null);
        }
    }

    function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
    {
        $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode, "data" => $data);
        echo json_encode($response);
    }