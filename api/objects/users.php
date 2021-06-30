<?php
class Users {
    private $conn;
    private $table_name = "user";

    // Constructor which accept the database object
    public function __construct($db) {
        $this->conn = $db;
    }

    function getAllUsersData() {

        // Select all users steps
        $query = "Select * from ". $this -> table_name;

        // prepare query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function getUsersByEmail($email) {
        // Select all users steps for the given id
        $query = "Select * from " . $this -> table_name ." Where email = '".$email."'";

        // prepared query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        return $stmt;
    }

    function newUserRegistration($user) {
        // Insert user's information to the table
        $query = "Insert into " .$this -> table_name ." (name,email,password) 
            values ('{$user['name']}','{$user['email']}','{$user['password']}')";

        // prepared query statement
        $stmt = $this->conn->prepare($query);

        //print_r($stmt);
        // Execute Query
        return $stmt->execute();
    }
}