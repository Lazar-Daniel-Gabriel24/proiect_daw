<?php
function connectToDatabase() {
    $host = 'localhost';
    $username = 'root';
    $password = '';
    $database = 'proiect';

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

function validateEmail($email) {
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function addUser($name, $email, $phone, $message, $password) {
    $conn = connectToDatabase();
    $stmt = mysqli_prepare($conn, "INSERT INTO `users` (`name`, `email`, `phone`, `message`, `password`) VALUES (?, ?, ?, ?, ?)");
    mysqli_stmt_bind_param($stmt, "sssss", $name, $email, $phone, $message, $password);

    $query = mysqli_stmt_execute($stmt);

    mysqli_close($conn);

    return $query;
}
function verifyPassword($inputPassword, $Password) {
    return $inputPassword === $Password;
}


function getUserByEmail($email) {
    $conn = connectToDatabase();

    $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE `email` = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);


    mysqli_close($conn);

    return mysqli_fetch_assoc($result);
}

function getUserById($id) {
    $conn = connectToDatabase();

    $stmt = mysqli_prepare($conn, "SELECT * FROM `users` WHERE `id` = ?");
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);

    $result = mysqli_stmt_get_result($stmt);

    mysqli_close($conn);

    return mysqli_fetch_assoc($result);
}
?>
