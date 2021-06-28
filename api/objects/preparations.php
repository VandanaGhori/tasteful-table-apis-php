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

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function getPreparationsByID($id) {
        // Select all preparations steps for the given id
        $query = "Select * from " . $this -> table_name ." Where rec_id = " .$id;

        // prepared query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }
}

