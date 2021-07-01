<?php

class Ingredients
{
    private $conn;
    private $table_name = "ingredients";

    // Constructor which accept the database object
    public function __construct($db)
    {
        $this->conn = $db;
    }

    function getAllIngredients()
    {

        // Select all ingredients
        $query = "Select * from " . $this->table_name;

        //return $stmt;

        return $this->query_execute($query);
    }

    function getIngredientsByID($id)
    {
        // Select all ingredients for the given id
        $query = "Select * from " . $this->table_name . " Where rec_id = " . $id;

        //return $stmt;

        return $this->query_execute($query);
    }

    function query_execute($query)
    {
        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        //print_r($stmt);
        $num = $stmt->rowCount();

        // check if more than 0 record found
        if ($num > 0) {
            $ingredients_arr = array();

            // pushing the values in key name "data"
            //$ingredients_arr["data"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $ingredients_item = array(
                    "id" => $row['id'],
                    "rec_id" => $row['rec_id'],
                    "name" => $row['name'],
                    "qty" => $row['qty'],
                    "img_url" => $row['img_url']
                );

                array_push($ingredients_arr, $ingredients_item);
            };

            // set response code - 200 OK
            //http_response_code(200);

            // show ingredients data in JSON format
            //echo json_encode($ingredients_arr, JSON_UNESCAPED_SLASHES);
            return $ingredients_arr;
        } else {

            // set response code - 404 not found
            //http_response_code(404);

            // tell the user no ingredients are found
            //echo json_encode(array("message" => "No Ingredients Found."));
            return null;
        }
    }
}

