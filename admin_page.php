<?php
session_start();
include('connect.php');
include('functions.php');

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

$user_id = $_SESSION['user_id'];
$user_info = getUserById($user_id);
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Page</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
            background-color: #f4f4f4;
            color: #333;
        }
        header {
            background-color: #333;
            color: #fff;
            text-align: center;
            padding: 20px 0;
        }
        h1 {
            margin: 0;
        }
        main {
            flex: 1;
            padding: 20px;
            text-align: center;
        }
        p {
            line-height: 1.6;
            font-size: 18px;
        }
        form {
            text-align: center;
            margin-top: 20px;
        }
        #logout-btn {
            padding: 10px 20px;
            background-color: #333;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        #logout-btn:hover {
            background-color: #555;
        }
    </style>
</head>
<body>
    <header>
        <h1>Bun venit pe pagina de admin, <?php echo $user_info['name']; ?>!</h1>
    </header>
    
    <main>
        <p>Aici poți adăuga orice informații sau funcționalități specifice utilizatorilor cu rol de admin.</p>
    </main>

    <form action="logout.php" method="post">
        <input type="submit" id="logout-btn" name="logout" value="Logout">
    </form>
</body>
</html>
