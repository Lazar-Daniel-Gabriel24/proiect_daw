<?php
include 'connect.php';
include 'functions.php';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    if ($email && $password) {
        $user = getUserByEmail($email);

        if ($user && verifyPassword($password, $user['password'])) {
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header('Location: pagina_web.php');
            exit();
        } else {
            echo 'Login Failed';
        }
    } else {
        echo 'Error: Email and password are required';
    }
}
?>

<form action="login.php" method="post">
    <label for="email">Email:</label>
    <input type="text" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br><br>

    <input type="submit" name="submit" value="Login">
</form>
