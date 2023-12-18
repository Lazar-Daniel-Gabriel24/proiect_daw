<?php
include 'connect.php';
include 'functions.php';

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
    
    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') : '';
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : '';
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : '';

    // reCAPTCHA verification
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_secret = '6LceZzQpAAAAALwvRKixKiNWDKyxaZYOyU0Fw6l8'; // Adaugă cheia ta secretă
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}";
    $recaptcha_data = json_decode(file_get_contents($recaptcha_url));

    if ($recaptcha_data->success) {
        // Continuă cu procesarea formularului
        if ($name && $email && $phone && $message && $password) {
            // Validează adresa de email
            if (!validateEmail($email)) {
                echo 'Error: Invalid email address';
                exit();
            }

            // Validează celelalte date (poți adăuga și altele în funcție de nevoi)

            $role = 'user';
            // Folosește o funcție de hashing pentru a stoca parola
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

            if (addUser($name, $email, $phone, $message, $hashedPassword, $role)) {
                echo htmlspecialchars('Registration Successful', ENT_QUOTES, 'UTF-8');
            } else {
                echo htmlspecialchars('Error Occurred: Registration failed', ENT_QUOTES, 'UTF-8');
            }
        } else {
            echo 'Error: All fields are required';
        }
    } else {
        echo 'Error: reCAPTCHA verification failed';
    }
}
?>
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<form action="register.php" method="post">
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
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

    <!-- Adaugă câmpul pentru token-ul anti-CSRF -->
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">
    
    <!-- Adaugă reCAPTCHA -->
    <div class="g-recaptcha" data-sitekey="6LceZzQpAAAAAHEwYT9GUPv-Y5jSOgNmtybHCah-"></div>

    <input type="submit" name="submit" value="Register">
</form>
