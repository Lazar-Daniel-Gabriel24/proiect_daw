<?php
function connectToDatabase() {
    $host = 'localhost';
    $username = 'id21496472_daniel';
    $password = 'Daniel1234.';
    $database = 'id21496472_proiectsite';

    $conn = mysqli_connect($host, $username, $password, $database);

    if (!$conn) {
        die("Connection failed: " . mysqli_connect_error());
    }

    return $conn;
}

function validateEmail($email) {
    // Folosește filter_var pentru a valida adresa de email
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

function addUser($name, $email, $phone, $message, $password, $role = 'user') {
    $conn = connectToDatabase();

    // Folosește interogare pregătită pentru a evita SQL injection
    $stmt = mysqli_prepare($conn, "INSERT INTO `users` (`name`, `email`, `phone`, `message`, `password`, `role`, `status`) VALUES (?, ?, ?, ?, ?, ?, 'active')");
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $phone, $message, $password, $role);

    $query = mysqli_stmt_execute($stmt);

    mysqli_close($conn);

    return $query;
}

function verifyPassword($inputPassword, $storedPassword) {
    // Folosește o funcție de hashing (de exemplu, password_hash) pentru a stoca și verifica parolele
    return password_verify($inputPassword, $storedPassword);
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
