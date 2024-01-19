<?php
include 'connect.php';
include 'functions.php';

session_start();

if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

$csrf_token = $_SESSION['csrf_token'];

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    if (!isset($_POST['csrf_token']) || !hash_equals($_SESSION['csrf_token'], $_POST['csrf_token'])) {
        die("CSRF token validation failed");
    }

    $name = isset($_POST['name']) ? htmlspecialchars($_POST['name'], ENT_QUOTES, 'UTF-8') : '';
    $email = isset($_POST['email']) ? htmlspecialchars($_POST['email'], ENT_QUOTES, 'UTF-8') : '';
    $phone = isset($_POST['phone']) ? htmlspecialchars($_POST['phone'], ENT_QUOTES, 'UTF-8') : '';
    $message = isset($_POST['message']) ? htmlspecialchars($_POST['message'], ENT_QUOTES, 'UTF-8') : '';
    $password = isset($_POST['password']) ? htmlspecialchars($_POST['password'], ENT_QUOTES, 'UTF-8') : '';

    $recaptcha_response = $_POST['g-recaptcha-response'];
    $recaptcha_secret = '6LdnbDQpAAAAAHlLxqKXTST-aB9_bcEAzjz7ZhW_';  // Replace with your actual reCAPTCHA secret key
    $recaptcha_url = "https://www.google.com/recaptcha/api/siteverify";
    $recaptcha_data = [
        'secret' => $recaptcha_secret,
        'response' => $recaptcha_response,
    ];

    $recaptcha_options = [
        'http' => [
            'method' => 'POST',
            'header' => "Content-type: application/x-www-form-urlencoded\r\n",
            'content' => http_build_query($recaptcha_data),
        ],
    ];

    $recaptcha_context = stream_context_create($recaptcha_options);
    $recaptcha_result = file_get_contents($recaptcha_url, false, $recaptcha_context);
    $recaptcha_data = json_decode($recaptcha_result);

    if ($recaptcha_data && $recaptcha_data->success) {
        if (!$name || !$email || !$phone || !$message || !$password) {
            echo 'Error: All fields are required';
            exit();
        }

        if (!validateEmail($email)) {
            echo 'Error: Invalid email address';
            exit();
        }

        // Hash the password before insertion
        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        // Use the addUser function to insert data into the 'users' table
        $insertResult = addUser($name, $email, $phone, $message, $hashedPassword, 'neverified');

        if ($insertResult) {
            // Store registration data in the session
            $_SESSION['registration_data'] = [
                'email' => $email,
                'name' => $name,
                'phone' => $phone,
                'message' => $message,
                'password' => $hashedPassword,  // Updated to use hashed password
                // Other information you want to save
            ];

            header("Location: verification.php");
            exit();
        } else {
            echo 'Error: Failed to insert user data into the database';
        }
    } else {
        echo 'Error: reCAPTCHA verification failed';
    }
}
?>

<!-- Include reCAPTCHA script -->
<script src="https://www.google.com/recaptcha/api.js" async defer></script>

<!-- Display the registration form -->
<form action="register.php" method="post">
    <!-- Field for the anti-CSRF token -->
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <!-- Other form fields -->
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

    <!-- Hidden field for the anti-CSRF token -->
    <input type="hidden" name="csrf_token" value="<?php echo $csrf_token; ?>">

    <!-- reCAPTCHA -->
    <div class="g-recaptcha" data-sitekey="6LdnbDQpAAAAADYkJz1LolR1yARAcpfik42eXeSo"></div>

    <!-- Register button -->
    <input type="submit" name="submit" value="Register">
</form>
