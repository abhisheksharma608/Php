README - BGT Assessment ZIP
==========================

Files included:
- index.html
- send_email.php        (PHPMailer template - requires composer install)
- send_email_simple.php (Simple fallback using PHP mail())

What to do next (recommended, secure):
1) Upload all files to your PHP-enabled hosting folder (same folder).
2) Install PHPMailer on the server:
   - If you have shell access: run `composer require phpmailer/phpmailer`
     This will create the `vendor/` directory required by send_email.php
   - If you don't have shell access, you can generate composer vendor locally and upload `vendor/`
3) Open send_email.php and replace the line:
       $smtpPass = 'REPLACE_WITH_YOUR_APP_PASSWORD';
   with your Gmail App Password (not your normal Google password).
   Note: To use Gmail with SMTP, you must enable 2-Step Verification and create an App Password:
     - Google Account -> Security -> 2-Step Verification -> ON
     - Then 'App passwords' -> create a Mail app password -> copy it here.
4) In your index.html, ensure the frontend sends the test result as JSON POST to `send_email.php`.
   - If you prefer the simple fallback, change the fetch URL to `send_email_simple.php` (but attachments won't be sent).
5) Test by taking a test on the page and check your Gmail (also spam folder).

Important security notes:
- Never commit your real password/App Password into public repos.
- Use App Password (recommended) with Gmail, not your main Google password.

If you want, I can:
- Modify index.html to point to send_email.php automatically (I included fetch example earlier).
- Provide instructions to store results in CSV or MySQL.

Good luck! - Assistant
