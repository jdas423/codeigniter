<?php
require_once __DIR__ . '/index.php';
$mysqli = new mysqli("localhost", "root", "Mysql@aps9876", "experiment");

// Check connection
if ($mysqli->connect_errno) {
    echo "Failed to connect to MySQL: " . $mysqli->connect_error;
} else {
    echo "Connection successful!";

    $user=new App\Models\Usermodel();
    $data["data"]=$user->findAll();
    echo json_encode($data["data"]);
}

