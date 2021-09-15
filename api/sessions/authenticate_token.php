<?php
    header("Access-Control-Allow-Origin: *");
    header("Content-Type: application/json; charset=UTF-8");

    // include database and object files
    include_once '../config/database.php';
    include_once '../objects/sessions.php';

    // instantiate database and ingredients object
    $database = new Database();
    $db = $database->getConnection();

    // call Constructor method of Ingredients and pass db to it and get connection as a response
    $sessions = new Sessions($db);
    $user = new Users($db);



