<?php
    include_once '../objects/sessions.php';

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

            //$headers = getallheaders();
            //$token = $headers['api_key'];

            $user_session = new Sessions($db);

            //$user_id = $user_session->getUserIdFromToken($token);
            $user_id = $user_session->getUserIdFromToken("e3d08d7c12461ab8b0a27c7cf7d777921");

            if($user_id != 0 || $user_id != null) {
                //sendResponse(true,"Logged in user's id is fetched for the given token.",200,$user_id);
                $this->addFavouriteRecipe($user_id,$rec_id);
                return 1;
            } else {
                return 0;
            }
        }

        public function isFavouriteRecipe($user_id, $rec_id) {
            $query = "Select * from " . $this->table_name . " where user_id = " . $user_id . " and rec_id = " . $rec_id;

            //print_r("Query=".$query);
            $prepared_query = $this->conn->prepare($query);

            $prepared_query->execute();

            $num = $prepared_query->rowCount();

            //print("Result = " . $num);
            if($num>0) {
                return true;
            } else {
                return false;
            }
        }

        public function disLikeRecipe($rec_id)
        {
            //$_SERVER['HTTP']
            $database = new Database();
            $db = $database->getConnection();

            //$headers = getallheaders();
            //$token = $headers['api_key'];

            $user_session = new Sessions($db);

            //$user_id = $user_session->getUserIdFromToken($token);
            $user_id = $user_session->getUserIdFromToken("e3d08d7c12461ab8b0a27c7cf7d777921");

            if($user_id != 0 || $user_id != null) {
                $this->removeFavouriteRecipe($user_id,$rec_id);
                return 1;
            } else {
                return 0;
            }
        }

        public function getAllFavouriteRecipes($user_id)
        {
            // Select all recipes from user_favourite
            $query = "SELECT * FROM recipe WHERE id in (SELECT rec_id FROM " . $this->table_name . " WHERE user_id = " . $user_id .")";

            return $this->query_execute($query);
        }

        public function query_execute($query){

            $stmt = $this->conn->prepare($query);

            //print_r($stmt);
            // execute query
            $stmt->execute();

            //print_r($stmt);
            $num = $stmt->rowCount();

            // check if more than 0 record found
            if ($num > 0) {
                $favourites_arr = array();
                // pushing the values in key name "data"
                //$ingredients_arr["data"] = array();

                while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {

                    $favourites_item = array(
                        "id" => $row['id'],
                        "name" => $row['name'],
                        "img_url" => $row['img_url'],
                        "time" => $row['time']
                    );

                    array_push($favourites_arr, $favourites_item);
                };

                // set response code - 200 OK
                //http_response_code(200);

                // show ingredients data in JSON format
                //echo json_encode($ingredients_arr, JSON_UNESCAPED_SLASHES);
                return $favourites_arr;
            } else {

                // set response code - 404 not found
                //http_response_code(404);

                // tell the user no ingredients are found
                //echo json_encode(array("message" => "No Ingredients Found."));
                return null;
            }
        }

        public function addFavouriteRecipe($user_id, $rec_id)
        {
            if($user_id == 0 || $rec_id == 0) { return; }

            // Add user favourites
            $query = "Insert into " .$this->table_name. " (user_id,rec_id) values (" . $user_id . "," .$rec_id . ")";

            $prepared_query = $this->conn->prepare($query);

            $prepared_query->execute();
        }

        public function removeFavouriteRecipe($user_id,$rec_id) {
            if($user_id == 0 || $rec_id == 0) { return; }

            // Remove user favourites
            $query = "Delete from " .$this->table_name. " where rec_id = " .$rec_id. " and user_id = " .$user_id;

            $prepared_query = $this->conn->prepare($query);

            $prepared_query->execute();
        }
    }