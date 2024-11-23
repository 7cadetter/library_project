<!--
This file is the one that connects to the database 'library'. It is called whenever another file
in the website needs to connects to the database. This allows them to access the contents of the libary DB.
-->

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "library";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}
?>