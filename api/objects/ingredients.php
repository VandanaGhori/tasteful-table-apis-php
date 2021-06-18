<?php
    class Ingredients {
            private $conn;
            private $table_name = "ingredients";

            // Constructor which accept the database object
            public function __construct($db) {
                $this->conn = $db;
            }

            function getAllIngredients() {

                // Select all ingredients
                $query = "Select * from ". $this -> table_name;

                // prepare query statement
                $stmt = $this->conn->prepare($query);

                // execute query
                $stmt->execute();

                return $stmt;
            }

            function getIngredientsByID($id) {
            // Select all ingredients for the given id
                $query = "Select * from " . $this -> table_name ." Where rec_id = " .$id;

                // prepared query statement
                $stmt = $this->conn->prepare($query);

                // execute query
                $stmt->execute();

                return $stmt;
            }
    }
