<?php

$servername = "localhost";
$dbname = "invitesystem";
$username = "root";
$password = "";

$connection = new mysqli($servername, $username, $password, $dbname);

if($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}

?>