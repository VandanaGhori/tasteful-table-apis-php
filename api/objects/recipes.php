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

            return $stmt;
        }
    }