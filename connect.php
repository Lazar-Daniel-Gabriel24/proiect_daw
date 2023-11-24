<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "proiect";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




function closeDBConnection() {
    global $conn;
    mysqli_close($conn);
}
?>
