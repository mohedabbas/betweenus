<?php

namespace App\Utility;

// Inclusion manuelle de PHPMailer
require_once __DIR__ . '/../Libs/PHPMailer/src/PHPMailer.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/SMTP.php';
require_once __DIR__ . '/../Libs/PHPMailer/src/Exception.php';

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;


class Mailer
{
    private $mail;

    public function __construct()
    {
        $this->mail = new PHPMailer(true);
        $this->mail->isSMTP();
        $this->mail->Host = 'smtp.gmail.com';
        $this->mail->SMTPAuth = true;
        $this->mail->Username = 'projetphpscratch@gmail.com';
        $this->mail->Password = 'cxvp lzrf icwq wiss';
        $this->mail->SMTPSecure = 'tls';
        $this->mail->Port = 587;
        $this->mail->Debugoutput = 'html';
        $this->mail->SMTPDebug  = 0;   // Pas de logs à l’écran
    }

    public function sendMail(string $to, string $subject, string $body): void
    {
        try {
            // L'adresse "From" => pareil que l'adresse d'envoi
            $this->mail->setFrom('projetphpscratch@gmail.com', 'BetweenUs');
            $this->mail->addAddress($to);
            $this->mail->isHTML(true);
            $this->mail->Subject = $subject;
            $this->mail->Body = $body;
            $this->mail->send();
        } catch (Exception $e) {
            echo "Message could not be sent. Mailer Error: {$this->mail->ErrorInfo}";
        }
    }
}
