<?php
$servername = "localhost";
$username = "id21496472_daniel";
$password = "Daniel1234.";
$dbname = "id21496472_proiectsite";

$conn = mysqli_connect($servername, $username, $password, $dbname);

if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}




// Închide conexiunea la baza de date după ce ai terminat operațiunile
function closeDBConnection() {
    global $conn;
    mysqli_close($conn);
}
?>
