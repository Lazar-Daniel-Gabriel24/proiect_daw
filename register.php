<?php
include 'connect.php';
include 'functions.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $name = isset($_POST['name']) ? $_POST['name'] : '';
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $message = isset($_POST['message']) ? $_POST['message'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($name && $email && $phone && $message && $password) {
        if (addUser($name, $email, $phone, $message, $password)) {
            echo 'Registration Successful';
        } else {
            echo 'Error Occurred: Registration failed';
        }
    } else {
        echo 'Error: All fields are required';
    }
}
?>
<form action="register.php" method="post">
    <label for="name">Name:</label>
    <input type="text" name="name" required><br><br>

    <label for="email">Email:</label>
    <input type="text" name="email" required><br><br>

    <label for="phone">Phone:</label>
    <input type="text" name="phone" required><br><br>

    <label for="message">Message:</label>
    <input type="text" name="message" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br><br>

    <input type="submit" name="submit" value="Register">
</form>
