# This shell script automatically sets up the CRON job on your system.
# It appends a cron entry that runs cron.php every 5 minutes using PHP CLI.
# Running this script will activate the periodic sending of GitHub timeline updates.

CRON_JOB="*/5 * * * * php $(pwd)/cron.php"
(crontab -l; echo "$CRON_JOB") | sort -u | crontab -
echo "CRON job scheduled to run every 5 minutes."
