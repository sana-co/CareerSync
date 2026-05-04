<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class Mailer
{
    private static function makeMailer(): PHPMailer
    {
        require_once __DIR__ . '/../../../vendor/PHPMailer/src/Exception.php';
        require_once __DIR__ . '/../../../vendor/PHPMailer/src/PHPMailer.php';
        require_once __DIR__ . '/../../../vendor/PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        // SMTP config (Gmail)
        $mail->isSMTP();
        $mail->Host       = 'smtp.gmail.com';
        $mail->SMTPAuth   = true;
        $mail->Username   = 'careersync1@gmail.com';
        $mail->Password   = 'kutbfksfllmtdxkr';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port       = 587;

        $mail->isHTML(true);
        $mail->CharSet = 'UTF-8';

        return $mail;
    }

    public static function sendVerificationEmail(string $toEmail, string $token): bool
    {
        try {
            $mail = self::makeMailer();

            $verifyUrl = ROOT . 'emailverification/verify?token=' . urlencode($token);

            $mail->setFrom('careersync1@gmail.com', 'CareerSync');
            $mail->addAddress($toEmail);

            $mail->Subject = 'CareerSync – Verify Your Email Address';
            $mail->Body = "
                <div style='font-family:Arial,sans-serif;max-width:600px;margin:auto;padding:30px;border:1px solid #e0e0e0;border-radius:8px;'>
                    <h2 style='color:#2c3e50;'>Welcome to CareerSync!</h2>
                    <p>Thank you for registering. Please verify your email address by clicking the button below.</p>
                    <p style='text-align:center;margin:30px 0;'>
                        <a href='{$verifyUrl}'
                           style='background-color:#007bff;color:#ffffff;padding:12px 28px;
                                  text-decoration:none;border-radius:5px;font-size:16px;display:inline-block;'>
                            Verify Email Address
                        </a>
                    </p>
                    <p style='color:#888;font-size:13px;'>This link will expire in <strong>24 hours</strong>.</p>
                    <p style='color:#888;font-size:13px;'>If you did not create an account, you can safely ignore this email.</p>
                    <hr style='border:none;border-top:1px solid #eee;margin-top:30px;'>
                    <p style='color:#aaa;font-size:12px;text-align:center;'>CareerSync &copy; " . date('Y') . "</p>
                </div>
            ";
            $mail->AltBody = "Welcome to CareerSync! Please verify your email by visiting: {$verifyUrl}\n\nThis link expires in 24 hours.";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: " . $e->getMessage());
            return false;
        }
    }

    public static function sendTestMail($toEmail): bool
    {
        try {
            $mail = self::makeMailer();

            $mail->setFrom('careersync1@gmail.com', 'CareerSync');
            $mail->addAddress($toEmail);

            $mail->Subject = 'CareerSync Registration Successful';
            $mail->Body    = '<h3>Welcome to CareerSync</h3><p>Your registration was successful.</p>';
            $mail->AltBody = 'Welcome to CareerSync. Your registration was successful.';

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: " . $e->getMessage());
            return false;
        }
    }

    public static function feedBackEmail(string $fromEmail, string $fromName, string $message): bool
    {
        try {
            $mail = self::makeMailer();

            $mail->setFrom('careersync1@gmail.com', 'CareerSync Feedback');
            $mail->addReplyTo($fromEmail, $fromName);
            $mail->addAddress('careersync1@gmail.com');

            $safeName  = htmlspecialchars($fromName, ENT_QUOTES, 'UTF-8');
            $safeEmail = htmlspecialchars($fromEmail, ENT_QUOTES, 'UTF-8');
            $safeMsg   = nl2br(htmlspecialchars($message, ENT_QUOTES, 'UTF-8'));

            $mail->Subject = 'New Contact Message - CareerSync';
            $mail->Body = "
                <h3>New Contact Message</h3>
                <p><strong>From:</strong> {$safeName} ({$safeEmail})</p>
                <p><strong>Message:</strong></p>
                <p>{$safeMsg}</p>
            ";

            $mail->AltBody = "From: $fromName ($fromEmail)\n\n$message";

            $mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Mail Error: " . $e->getMessage());
            return false;
        }
    }
}