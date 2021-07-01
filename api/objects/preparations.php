<?php
class Preparations {
    private $conn;
    private $table_name = "preparation";

    // Constructor which accept the database object
    public function __construct($db) {
        $this->conn = $db;
    }

    function getAllPreparationData() {

        // Select all preparations steps
        $query = "Select * from ". $this -> table_name;

        //return $stmt;

        //method for executing query and preparing the data
        return $this->query_execute($query);
    }

    function getPreparationsByID($id) {
        // Select all preparations steps for the given id
        $query = "Select * from " . $this -> table_name ." Where rec_id = " .$id;

        //return $stmt;

        //method for executing query and preparing the data
        return $this->query_execute($query);
    }

    function query_execute($query) {
        // prepared query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $num = $stmt->rowCount();

        // check if more than 0 record found
        if ($num > 0) {
            $preparation_arr = array();

            // pushing the values in key name "data"
            //$preparation_arr["data"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $preparation_item = array(
                    "id" => $row['id'],
                    "rec_id" => $row['rec_id'],
                    "steps" => $row['steps'],
                    "order_no" => $row['order_no']
                );

                array_push($preparation_arr, $preparation_item);
            };

            // set response code - 200 OK
            //http_response_code(200);

            // show preparation data in JSON format
            //echo json_encode($preparation_arr, JSON_UNESCAPED_SLASHES);
            //print_r($preparation_arr);
            return $preparation_arr;
        } else {

            // set response code - 404 not found
            //http_response_code(404);

            // tell the user no preparations steps are found
            //echo json_encode(array("message" => "No Preparations Steps Found."));
            return null;
        }
    }
}

