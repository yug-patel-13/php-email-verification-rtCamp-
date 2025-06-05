<!-- This script is triggered by the CRON job every 5 minutes. It simply calls the function to send GitHub updates to all registered emails. -->

<?php
include 'functions.php';
sendGitHubUpdatesToSubscribers();
?>

// 📄 src/setup_cron.sh
#!/bin/bash
CRON_JOB="*/5 * * * * php $(pwd)/cron.php"
(crontab -l; echo "$CRON_JOB") | sort -u | crontab -
echo "CRON job scheduled to run every 5 minutes."
?>
