<!-- 
  STEP 1: Include functions from functions.php
  STEP 2: If email is submitted via the form:
          - Generate a 6-digit verification code
          - Save it in verification_codes.txt
          - Send email with the code
  STEP 3: If verification code is submitted:
          - Check if it matches a saved email-code pair
          - If match found, register the email in registered_emails.txt
          - Show a success message
  STEP 4: Display form for email input and code verification -->

<?php include 'functions.php'; ?>
<link rel="stylesheet" href="style.css">

<form method="post">
    <h1>rtCamp assignment </h1>
    <div id="rt">
    <input type="email" name="email" placeholder="enter the mail" required>
    <button id="submit-email">Submit</button><br><br>
    <input type="text" name="verification_code" maxlength="6" placeholder="enter the verification code" required>
    <button id="submit-verification">Verify</button>
    </div>
</form>
<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? null;
    $code = $_POST['verification_code'] ?? null;

    if ($email && !$code) {
        $vcode = generateVerificationCode();
        file_put_contents('verification_codes.txt', "$email:$vcode" . PHP_EOL, FILE_APPEND);
        sendVerificationEmail($email, $vcode);
    }

    if ($code && $email) {
        $lines = file('verification_codes.txt', FILE_IGNORE_NEW_LINES);
        foreach ($lines as $line) {
            [$savedEmail, $savedCode] = explode(':', $line);
            if ($savedEmail === $email && trim($savedCode) === $code) {
                registerEmail($email);
                echo "<p>Email verified and registered.</p>";
            }
        }
    }
}
?>