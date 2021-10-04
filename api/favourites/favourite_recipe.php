<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    include_once '../config/database.php';
    include_once '../objects/favourites.php';

    $database = new Database();
    $db = $database->getConnection();

    $favourites = new Favourites($db);

    if($_SERVER['REQUEST_METHOD'] === 'POST') {
        if(!isset($_POST['rec_id'])) {
            sendResponse(false, "parameter(s) are missing.", 402, null);
        } else {
            $result = $favourites->likeRecipe($_POST['rec_id']);
            // 1 for success 0 for failure
            if($result) {
                sendResponse(true,"Recipe is added into Favourite list.",200,1);
            } else {
                sendResponse(false,"User is not authorised. Please try to login and then add it to favourite list.",404,null);
            }
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'GET') {
        $database = new Database();
        $db = $database->getConnection();

        $user_session = new Sessions($db);

        if(!isset($_GET['rec_id'])) {
            sendResponse(false, "parameter(s) are missing.", 402, null);
        } else {
            //$user_id = $user_session->getUserIdFromToken($token);
            $user_id = $user_session->getUserIdFromToken("e3d08d7c12461ab8b0a27c7cf7d777921");

            if ($user_id != 0 || $user_id != null) {
                $isUsersFavourite = $favourites->isFavouriteRecipe($user_id,$_GET['rec_id']);
                if($isUsersFavourite) {
                    sendResponse(true,"This is user's favourite recipe.",200,true);
                } else {
                    sendResponse(false,"This is not user favourite recipe.",404,false);
                }
            }
        }
    }

    if($_SERVER['REQUEST_METHOD'] === 'DELETE') {
        if(!isset($_DELETE['rec_id'])) {
            sendResponse(false, "parameter(s) are missing.", 402, null);
        } else {
            $result = $favourites->disLikeRecipe($_DELETE['rec_id']);
            // 1 for success 0 for failure
            if($result) {
                sendResponse(true,"Recipe is removed from the user's favourite list.",200,1);
            } else {
                sendResponse(false,"User is not authorised. Please try to login and then delete it.",404,null);
            }
        }
    }

    function sendResponse($success = true, $error_msg, $errorCode = 504, $data)
    {
        $response = array("success" => $success, "error" => $error_msg, "error_code" => $errorCode, "data" => $data);
        echo json_encode($response);
    }

