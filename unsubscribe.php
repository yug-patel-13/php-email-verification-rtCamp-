/**
 * unsubscribe.php
 *
 * STEP 1: Include functions from functions.php
 * STEP 2: If unsubscribe email is submitted:
 *         - Generate a code
 *         - Save it in unsubscribe_codes.txt
 *         - Send email with the code
 * STEP 3: If unsubscribe verification code is submitted:
 *         - Match code with email
 *         - If matched, remove the email from registered_emails.txt
 *         - Show confirmation message
 * STEP 4: Display unsubscribe form
 */

<?php include 'functions.php'; ?>

<link rel="stylesheet" href="style.css">

<form method="post">
    <input type="email" name="unsubscribe_email" required>
    <button id="submit-unsubscribe">Unsubscribe</button><br><br>
    <input type="text" name="unsubscribe_verification_code">
    <button id="verify-unsubscribe">Verify</button>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['unsubscribe_email'] ?? null;
    $code = $_POST['unsubscribe_verification_code'] ?? null;

    if ($email && !$code) {
        $vcode = generateVerificationCode();
        file_put_contents('unsubscribe_codes.txt', "$email:$vcode" . PHP_EOL, FILE_APPEND);
        sendUnsubscribeCode($email, $vcode);
    }

    if ($email && $code) {
        $lines = file('unsubscribe_codes.txt', FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            [$savedEmail, $savedCode] = explode(':', $line);
            if ($savedEmail === $email && trim($savedCode) === $code) {
                unsubscribeEmail($email);
                echo "<p>Successfully unsubscribed.</p>";
            }
        }
    }
}
?>