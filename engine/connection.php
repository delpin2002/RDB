<?php
$servername = "localhost";
$username = "root";
$password = "";
$database = "u1045575_rdb_channel";

$connection = new mysqli($servername, $username, $password, $database);

if ($connection->connect_error) {
    die("Connection failed: " . $connection->connect_error);
}
?>
