<?php
$servername = "localhost";
$username = "root"; 
$password = "";
$database = "db_collaborate";

$mysqli = new mysqli($servername, $username, $password, $database);

if ($mysqli->connect_error) {
    echo("Connection failed: " . $mysqli->connect_error);
} else {
}

?>
