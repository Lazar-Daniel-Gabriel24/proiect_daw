<?php
include 'connect.php';
include 'functions.php';

// La începutul formularului
session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Verifică token-ul anti-CSRF
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // reCAPTCHA verification
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_secret = '6LceZzQpAAAAALwvRKixKiNWDKyxaZYOyU0Fw6l8'; // Adaugă cheia ta secretă
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}";
    $recaptcha_data = json_decode(file_get_contents($recaptcha_url));

    if ($recaptcha_data->success) {
        // Continuă cu procesarea autentificării
        if ($email && $password) {
            $user = getUserByEmail($email);

            if ($user && verifyPassword($password, $user['password'])) {
                $_SESSION['user_id'] = $user['id'];

                // Verifică dacă contul este activ (poți adăuga și alte verificări)
                if ($user['status'] == 'active') {
                    // Verificăm rolul și redirecționăm în funcție de acesta
                    if ($user['role'] === 'admin') {
                        header('Location: admin_page.php');
                    } else {
                        header('Location: pagina_web.php');
                    }

                    exit();
                } else {
                    echo 'Error: Your account is not active';
                }
            } else {
                echo 'Login Failed';
            }
        } else {
            echo 'Error: Email and password are required';
        }
    } else {
        echo 'Error: reCAPTCHA verification failed';
    }
}
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<form action="login.php" method="post">
    <!-- Adaugă câmpul pentru token-ul anti-CSRF -->
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <label for="email">Email:</label>
    <input type="text" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br><br>

    <!-- Adaugă reCAPTCHA -->
    <div class="g-recaptcha" data-sitekey="6LceZzQpAAAAAHEwYT9GUPv-Y5jSOgNmtybHCah-"></div>

    <input type="submit" name="submit" value="Login">
</form>
