<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/recipe.php';

    // instantiate database and recipe object
    $database = new Database();
    $db = $database->getConnection();

    // initialize object and pass database to the constructor and return the connection
    $recipe = new Recipe($db);

    // query recipes
    $stmt = $recipe->getAllRecipe();
    $num = $stmt->rowCount();

    // check if more than 0 record found
    if($num > 0) {
        // recipes array
        $recipe_arr = array();

        //pushing the values in key name "data"
        $recipe_arr["data"] = array();

        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            // extract individual rows
            // this will make $row['name'] to
            // just $name only
            //extract($row);

            $recipe_item = array(
                "id" => $row['id'],
                "name" => $row['name'],
                "img_url" => $row['img_url'],
                "time" => $row['time']
            );

            array_push($recipe_arr["data"], $recipe_item);
        }

        // set response code - 200 OK
        http_response_code(200);

        // show recipes data in json format
        echo json_encode($recipe_arr);
    }
    else {
        // set response code - 404 not found
        http_response_code(404);

        // tell the user no recipes found
        echo json_encode(array("message" => "No Recipes Found."));
    }





