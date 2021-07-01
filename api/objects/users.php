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

        return $this->execute_query($query);
    }

    function getUsersByEmail($email) {
        // Select all users steps for the given id
        $query = "Select * from " . $this -> table_name ." Where email = '".$email."'";

        return $this->execute_query($query);

    }

    function checkUserLogin($user) {
        // Fetch user's information from the user table for authentication
        $query = "Select * from " . $this->table_name . "  
            where email = '". $user['email']."' and password = '" .$user['password']."'";

        //echo "Login Query\n";
        //print_r($query);
        return $this->execute_query($query);
    }

    function execute_query($query) {
        // prepared query statement
        $stmt = $this->conn->prepare($query);

        // execute query
        $stmt->execute();

        $num = $stmt->rowCount();

        // check if more than 0 record found
        if ($num > 0) {
            $preparation_arr = array();

            // pushing the values in key name "data"
           // $preparation_arr["data"] = array();

            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                $preparation_item = array(
                    "id" => $row['id'],
                    "name" => $row['name'],
                    "email" => $row['email'],
                    "password" => $row['password']
                );

                array_push($preparation_arr, $preparation_item);
            };

            // return preparation data to called method and convert into json format from there
            return $preparation_arr;
        } else {
            return null;
        }
    }

    function newUserRegistration($user)
    {
        // Insert user's information to the table
        $query = "Insert into " . $this->table_name . " (name,email,password) 
            values ('{$user['name']}','{$user['email']}','{$user['password']}')";

        // prepared query statement
        $stmt = $this->conn->prepare($query);

        // Execute Query
        $stmt->execute();

        return $stmt->rowCount();

    }
}