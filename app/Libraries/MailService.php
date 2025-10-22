<?php

namespace App\Libraries;

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class MailService
{
    public function sendMail($to, $subject, $message)
    {
        require_once APPPATH . 'Libraries/PHPMailer/src/Exception.php';
        require_once APPPATH . 'Libraries/PHPMailer/src/PHPMailer.php';
        require_once APPPATH . 'Libraries/PHPMailer/src/SMTP.php';

        $mail = new PHPMailer(true);

        try {
            // Server settings
            $mail->isSMTP();
            $mail->Host       = 'smtp.ionos.com';      // SMTP server
            $mail->SMTPAuth   = true;
            $mail->Username   = env('EMAIL_USR'); // Your email
            $mail->Password   = env('EMAIL_PASS');        // App password if Gmail
            $mail->SMTPSecure = 'tls';
            $mail->Port       = 587;

            // Recipients
            $mail->setFrom('mdarc-memberships@arrleb.org', 'MDARC Membership Chair');
            $mail->addAddress($to);

            // Content
            $mail->isHTML(true);
            $mail->Subject = $subject;
            $mail->Body    = $message;

            // Send email
            $mail->send();
            return 'Message has been sent successfully';
        } catch (Exception $e) {
            return "Message could not be sent. Mailer Error: {$mail->ErrorInfo}";
        }
    }
}
