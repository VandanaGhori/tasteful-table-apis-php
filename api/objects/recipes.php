<?php
        class Recipes {
        private $conn;
        private $table_name = "recipe";


        //Constructor which accept the database object
        public function __construct($db)
        {
            $this->conn = $db;
        }

        // read recipes
        function getAllRecipe() {

            // select all recipes
            $query = "Select * from ". $this->table_name;

            // prepare query statement
            $stmt = $this->conn->prepare($query);

            // execute query
            $stmt->execute();

            //return $stmt;

            $num = $stmt->rowCount();

            // check if more than 0 record found
            if ($num > 0) {
                // recipes array
                $recipe_arr = array();

                //pushing the values in key name "data"
                //$recipe_arr["data"] = array();

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

                    array_push($recipe_arr, $recipe_item);
                }

                // set response code - 200 OK
                //http_response_code(200);

                // show recipes data in json format
               // echo json_encode($recipe_arr, JSON_UNESCAPED_SLASHES);
               return $recipe_arr;
            } else {
                // set response code - 404 not found
                //http_response_code(404);

                // tell the user no recipes found
                return null;
            }
        }
    }