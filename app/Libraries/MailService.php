<?php

/* edited 1x*/

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function sendMail($to, $subject, $message): array
    {
        require_once APPPATH . 'Libraries/PHPMailer/src/Exception.php';
        require_once APPPATH . 'Libraries/PHPMailer/src/PHPMailer.php';
        require_once APPPATH . 'Libraries/PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);
        $username = env('EMAIL_USR') ?: env('EMAIL_USER');
        $password = env('EMAIL_PASS');
        $host = env('EMAIL_SMTP_HOST') ?: 'smtp.ionos.com';
        $port = (int) (env('EMAIL_SMTP_PORT') ?: 587);
        $crypto = env('EMAIL_SMTP_CRYPTO') ?: 'tls';
        $fromAddress = env('EMAIL_FROM') ?: $username;
        $fromName = env('EMAIL_FROM_NAME') ?: 'MDARC Membership Chair';

        try {
            if (empty($username) || empty($password) || empty($fromAddress)) {
                return [
                    'success' => false,
                    'message' => 'Missing email configuration. Check EMAIL_USR or EMAIL_USER, EMAIL_PASS, and EMAIL_FROM.',
                ];
            }

            // Server settings
            $mail->isSMTP();
            $mail->Host       = $host;
            $mail->SMTPAuth   = true;
            $mail->Username   = $username;
            $mail->Password   = $password;
            $mail->SMTPSecure = $crypto;
            $mail->Port       = $port;

            // Recipients
            $mail->setFrom($fromAddress, $fromName);
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send email
            $mail->send();
            return [
                'success' => true,
                'message' => 'Message has been sent successfully',
            ];
        } catch (Exception $e) {
            return [
                'success' => false,
                'message' => "Message could not be sent. Mailer Error: {$mail->ErrorInfo}",
            ];
        }
    }
}
