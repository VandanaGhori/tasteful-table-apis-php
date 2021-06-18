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

    // getting id from the parameters of URL
    $id = $_GET['id'];
    //print_r($id);

    // Query Ingredients
    $stmt = $ingredients->getAllIngredients();
    $stmt2 = $ingredients->getIngredientsByID($id);
    //print_r($stmt2);
    $num = $stmt2->rowCount();

    // check if more than 0 record found
    if ($num > 0) {
        $ingredients_arr = array();

        // pushing the values in key name "data"
        $ingredients_arr["data"] = array();

        while ($row = $stmt2->fetch(PDO::FETCH_ASSOC)) {

            $ingredients_item = array(
                "id" => $row['id'],
                "rec_id" => $row['rec_id'],
                "name" => $row['name'],
                "qty" => $row['qty'],
                "img_url" => $row['img_url']
            );

            array_push($ingredients_arr["data"], $ingredients_item);
        };

        // set response code - 200 OK
        http_response_code(200);

        // show ingredients data in JSON format
        echo json_encode($ingredients_arr, JSON_UNESCAPED_SLASHES);
    } else {

        // set response code - 404 not found
        http_response_code(404);

        // tell the user no recipes found
        echo json_encode(array("message" => "No Recipes Found."));
    }