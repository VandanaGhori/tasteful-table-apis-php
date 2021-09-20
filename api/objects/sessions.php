<?php
class Sessions {
    private $conn;
    private $table_name = "user_session";

    // constructor which accept the database objects
    public function __construct($db) {
        $this->conn = $db;
    }

    // Token generator for the new User
    function newUserTokenGenerator($user_id,$token) {
        // Insert and generate token for the new user
       // print("USer: ".$user_id);
       // print("Token: ".$token);
        $query = "Insert into " . $this->table_name . " values (" . $user_id.",'".$token."')";

        //print_r($query);
        // prepared query statement
        $stmt = $this->conn->prepare($query);

        // Execute Query
        $stmt->execute();
    }

    // Authorization of valid user through token
    function authenticateUserToken($user_id) {
        // Check whether user_id is exists into user_session table or not.
        $query = "Select * from " . $this->table_name . " where user_id = " . $user_id;

        //print_r($query);
        // prepared query statement
        $stmt = $this->conn->prepare($query);

        // Execute Query
        $stmt->execute();

        return $stmt->rowCount();
    }

    // Updating token after login successfully
    function updateToken($user_session_user_id,$updatedToken) {
        // Update token in user_session table after logging successfully.
        $query = "Update " . $this->table_name . " set token='" . $updatedToken . "' where user_id=" . "$user_session_user_id";

        // Preparing the Query
        $stmt = $this->conn->prepare($query);

        //print_r($stmt);

        // Execute Query
        $stmt->execute();
    }

    function getUserIdFromToken($token)
    {
        $query = "Select user_id from " . $this->table_name . " where token = " .$token;

        $stmt = $this->conn->prepare($query);

        return $stmt->execute();
    }
}
