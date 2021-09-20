<?php
    class Favourites
    {
        private $conn;
        private $table_name = "user_favourites";

        /**
         * Favourites constructor.
         * @param $conn
         */

        public function __construct($db)
        {
            $this->conn = $db;
        }

        public function likeRecipe($rec_id)
        {
            //$_SERVER['HTTP']
            $database = new Database();
            $db = $database->getConnection();

            $headers = getallheaders();
            $token = $headers['api_key'];

            $user_session = new Sessions($db);

            $user_id = $user_session->getUserIdFromToken($token);

            // Add user favourites
            $query = "Insert into " .$this->table_name. " (user_id,rec_id) values (" . $user_id . "," .$rec_id . ")";

            $prepared_query = $this->conn->prepare($query);

            return $prepared_query->execute();
        }

        public function disLikeRecipe($rec_id)
        {
            // Add user favourites
            $query = "Delete from " .$this->table_name. " where rec_id = " .$rec_id;

            $prepared_query = $this->conn->prepare($query);

            return $prepared_query->execute();
        }

        public function getAllFavouriteRecipes($user_id)
        {
            // Select all recipes from user_favourite
            $query = "Select * from " . $this->table_name . " where user_id = " . $user_id;

            $prepared_query = $this->conn->prepare($query);

            return $prepared_query->execute();
        }
    }