<?php
include 'connect.php';
include 'functions.php';

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    // Verify CSRF token
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $email = isset($_POST['email']) ? $_POST['email'] : '';
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    // reCAPTCHA verification
    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_secret = '6LdnbDQpAAAAAHlLxqKXTST-aB9_bcEAzjz7ZhW_'; // Add your secret key
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify?secret={$recaptcha_secret}&response={$recaptcha_response}";
    $recaptcha_data = json_decode(file_get_contents($recaptcha_url));

    if ($recaptcha_data->success) {
        // Continue with authentication process
        if ($email && $password) {
            $user = getUserByEmail($email);

            if ($user) {
                // Check if the account is verified
                if ($user['status'] == 'verified' && verifyPassword($password, $user['password'])) {
                    session_start();
                    $_SESSION['user_id'] = $user['id'];

                    // Redirect based on role
                    if ($user['role'] === 'admin') {
                        header('Location: admin_page.php');
                    } else {
                        header('Location: pagina_web.php');
                    }

                    exit();
                } else {
                    // If the account is not verified, display an error message
                    if ($user['status'] == 'neververified') {
                        echo 'Error: Your email is not verified. Please check your email for the verification code.';
                    } else {
                        echo 'Error: Your account is not active';
                    }
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
    <!-- Add the anti-CSRF token field -->
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <label for="email">Email:</label>
    <input type="text" name="email" required><br><br>

    <label for="password">Password:</label>
    <input type="password" name="password" required><br><br>

    <!-- Add reCAPTCHA -->
    <div class="g-recaptcha" data-sitekey="6LdnbDQpAAAAADYkJz1LolR1yARAcpfik42eXeSo"></div>

    <input type="submit" name="submit" value="Login">
</form>
