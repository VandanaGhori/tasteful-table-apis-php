<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/preparation.php';

    // instantiate database and preparations object
    $database = new Database();
    $db = $database->getConnection();

    // call Constructor method of Ingredients and pass db to it and get connection as a response
    $preparation = new Preparation($db);

    // Query Preparations
    if(isset($_GET['id'])) {
        // getting id from the parameters of URL
        $id = $_GET['id'];
        //print_r($id);
        $stmt = $preparation->getPreparationsByID($id);
    } else {
        $stmt = $preparation->getAllPreparationData();
    }

    $num = $stmt->rowCount();

    // check if more than 0 record found
    if ($num > 0) {
        $preparation_arr = array();

        // pushing the values in key name "data"
        $preparation_arr["data"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

            $preparation_item = array(
                "id" => $row['id'],
                "rec_id" => $row['rec_id'],
                "steps" => $row['steps'],
                "order_no" => $row['order_no']
            );

            array_push($preparation_arr["data"], $preparation_item);
        };

        // set response code - 200 OK
        http_response_code(200);

        // show preparation data in JSON format
        echo json_encode($preparation_arr, JSON_UNESCAPED_SLASHES);
    } else {

        // set response code - 404 not found
        http_response_code(404);

        // tell the user no preparations steps are found
        echo json_encode(array("message" => "No Preparations Steps Found."));
    }