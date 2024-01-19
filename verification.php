<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

include 'functions.php';

session_start();

// Check if the user is logged in
if (!isset($_SESSION['registration_data'])) {
    header("Location: register.php");
    exit();
}

// Retrieve registration data from the session
$registrationData = $_SESSION['registration_data'];
$email = $registrationData['email'];

// Check if the verification code is already generated
if (!isset($_SESSION['verification_code'])) {
    // Generate a new verification code
    $verificationCode = generateVerificationCode();

    // Store the verification code in the session
    $_SESSION['verification_code'] = $verificationCode;

    // Send the verification code via email
    $result = sendVerificationCode($email, $verificationCode);

    if (!$result) {
        echo 'Error: Failed to send verification code. Please try again.';
        exit();
    }
} else {
    // Use the previously generated verification code
    $verificationCode = $_SESSION['verification_code'];
}

?>

<!-- Display the verification form -->
<form action="verification.php" method="post">
    <label for="verification_code">Enter Verification Code:</label>
    <input type="text" name="verification_code" required><br><br>

    <input type="submit" name="submit" value="Verify">
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['submit'])) {
    $enteredCode = isset($_POST['verification_code']) ? htmlspecialchars($_POST['verification_code'], ENT_QUOTES, 'UTF-8') : '';
    echo 'Entered Code: ' . $enteredCode . '<br>';
    echo 'Generated Code: ' . $verificationCode . '<br>';

    // Check if the entered code matches the generated code
    if ($enteredCode == $verificationCode) {
        // Update user status to 'verified' in the database
        $user = getUserByEmail($email);

        if ($user) {
            $userId = $user['id'];
            updateUserStatus($userId, 'verified');

            // Clear registration data and verification code from the session
            unset($_SESSION['registration_data']);
            unset($_SESSION['verification_code']);

            echo 'Verification successful! You can now login.';
        } else {
            echo 'Error: User not found';
        }
    } else {
        echo 'Error: Invalid verification code';
    }
}
?>
