# PHP Email Verification and GitHub Timeline Subscription System

## Project Overview

This project is a PHP-based email verification and subscription system designed to allow users to subscribe to GitHub timeline updates via email. The system includes:

- User email registration with verification via a 6-digit code.
- Storing verified emails in a text file (no database used).
- Sending GitHub timeline updates to registered users every 5 minutes via a CRON job.
- Unsubscribe functionality with confirmation via email.
- All email communications are HTML formatted.

---


---

## Detailed Explanation of Each File

### 1. `index.php`

- Displays a user-facing HTML form with four always-visible sections:
  - **Email input** for registration.
  - **Verification code input** for confirming email verification.
  - **Unsubscribe email input** for unsubscribe requests.
  - **Unsubscribe code input** for confirming unsubscription.
- Handles form submissions for registration and verification.
- Uses functions from `functions.php` to generate codes, send verification emails, and register emails.

---

### 2. `functions.php`

Contains all core functionality as required:

- **`generateVerificationCode()`**  
  Generates a random 6-digit numeric verification code for email verification and unsubscribe confirmation.

- **`registerEmail($email)`**  
  Adds a verified email to `registered_emails.txt`, ensuring no duplicates.

- **`unsubscribeEmail($email)`**  
  Removes the specified email from `registered_emails.txt`.

- **`sendVerificationEmail($email, $code)`**  
  Sends an HTML email containing the 6-digit verification code to the user for registration.

- **`fetchGitHubTimeline()`**  
  Fetches the latest GitHub timeline data from `https://www.github.com/timeline`.  
  (Note: This fetch returns HTML content, not JSON.)

- **`formatGitHubData($data)`**  
  Parses and converts fetched GitHub timeline data into a simple HTML table structure for emailing.

- **`sendGitHubUpdatesToSubscribers()`**  
  Reads all registered emails from `registered_emails.txt`, fetches GitHub timeline data, formats it, and sends the HTML email update to each subscriber. Each email contains an unsubscribe link with the user’s email as a URL parameter.

---

### 3. `cron.php`

- Script intended to be run as a CRON job every 5 minutes.
- Calls `sendGitHubUpdatesToSubscribers()` from `functions.php` to send timeline updates to all subscribers.
- This automated task ensures users receive the latest GitHub activity without manual intervention.

---

### 4. `setup_cron.sh`

- A shell script to set up the CRON job automatically.
- When executed, it adds a CRON entry to run `cron.php` every 5 minutes.
- Avoids manual CRON configuration errors and ensures proper scheduling on supported systems.

---

### 5. `unsubscribe.php`

- Handles the unsubscribe workflow.
- When a user clicks the unsubscribe link in their email, they are directed here.
- Accepts user email input and sends a verification code for confirmation.
- Once the user enters the verification code, their email is removed from `registered_emails.txt`.
- Sends a confirmation email upon successful unsubscription.

---

### 6. `registered_emails.txt`

- A plain text file used as a simple data store.
- Stores all verified emails line-by-line.
- No database used, fulfilling project requirements.

---

### 7. `style.css`

- Contains basic styling for the forms on `index.php` and `unsubscribe.php`.
- Simple, clean design to keep all form elements visible and user-friendly.

---

## How to Run the Project Locally

1. **Setup PHP environment**:  
   Ensure PHP (recommended version 8.3, but 8.2 works fine) is installed. You can use XAMPP or any PHP server.

2. **Place the project in your web server root** (e.g., `htdocs` in XAMPP).

3. **Open your browser** and go to:  
   `http://localhost/your_project_folder/src/index.php`

4. **Register an email** by entering it and submitting the form.

5. **Check your email inbox** for the 6-digit verification code.

6. **Enter the verification code** in the form to confirm registration.

7. **To unsubscribe**, use the unsubscribe form on the same page or click the unsubscribe link sent with GitHub updates.

---

## How to Setup the CRON Job

Run the `setup_cron.sh` script from the terminal inside the `src/` directory:
