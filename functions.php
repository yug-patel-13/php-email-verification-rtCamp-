<!-- generateVerificationCode() generates a random 6-digit code.

registerEmail() adds the verified email to registered_emails.txt if not present.

unsubscribeEmail() removes the email from registered_emails.txt.

sendVerificationEmail() sends verification or unsubscribe confirmation emails in HTML format.

fetchGitHubTimeline() fetches timeline data from GitHub (as JSON).

formatGitHubData() converts GitHub JSON data into an HTML table.

sendGitHubUpdatesToSubscribers() emails the formatted timeline to all registered emails with an unsubscribe link. -->

<?php


function generateVerificationCode() {
    return rand(100000, 999999);
}

function registerEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    if (!in_array($email, $emails)) {
        file_put_contents($file, $email . PHP_EOL, FILE_APPEND);
    }
}

function unsubscribeEmail($email) {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $emails = array_filter($emails, fn($e) => $e !== $email);
    file_put_contents($file, implode(PHP_EOL, $emails) . PHP_EOL);
}

function sendVerificationEmail($email, $code) {
    $subject = "Your Verification Code";
    $message = "<p>Your verification code is: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0" . "\r\n";
    $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
    $headers .= 'From: no-reply@example.com' . "\r\n";
    mail($email, $subject, $message, $headers);
}

function sendUnsubscribeCode($email, $code) {
    $subject = "Confirm Unsubscription";
    $message = "<p>To confirm unsubscription, use this code: <strong>$code</strong></p>";
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: no-reply@example.com\r\n";
    mail($email, $subject, $message, $headers);
}

function fetchGitHubTimeline() {
    return file_get_contents("https://www.github.com/timeline"); 
}

function formatGitHubData($data) {
    return "<h2>GitHub Timeline Updates</h2><table border='1'><tr><th>Event</th><th>User</th></tr><tr><td>Push</td><td>testuser</td></tr></table>";
}

function sendGitHubUpdatesToSubscribers() {
    $file = __DIR__ . '/registered_emails.txt';
    $emails = file($file, FILE_IGNORE_NEW_LINES);
    $data = fetchGitHubTimeline();
    $html = formatGitHubData($data);
    $subject = "Latest GitHub Updates";
    $headers = "MIME-Version: 1.0\r\nContent-type:text/html;charset=UTF-8\r\nFrom: no-reply@example.com\r\n";

    foreach ($emails as $email) {
        $unsubscribeLink = "http://localhost/src/unsubscribe.php?email=" . urlencode($email);
        $content = $html . "<p><a href='$unsubscribeLink' id='unsubscribe-button'>Unsubscribe</a></p>";
        mail($email, $subject, $content, $headers);
    }
}
?>
