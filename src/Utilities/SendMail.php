<?php

namespace App\Utilities;

use Swift_Mailer;
use Swift_Message;
use Swift_SmtpTransport;

class SendMail {
    public function smtpMail(string $to, string $subject, string $message, string $replyto = "quizos.quiz@gmail.com", string $title = "ASPT ANATT"):bool {

        $smtpPasse = '0VhuUel@G';
        $smtpServer = 'smtp.hostinger.com';
        $smtpPort = '465';
        $smtpSecured = 'ssl';

        $from = "kasongoyeye@ctrue.online";

        try{
            $transport = (new Swift_SmtpTransport($smtpServer, $smtpPort, $smtpSecured)) 
                ->setUsername($from)
                ->setPassword($smtpPasse);
            $mailer = new Swift_Mailer($transport); 
            $content = (new Swift_Message())
                ->setSubject($subject)
                ->setFrom($from, $title)
                ->setReplyTo($replyto)
                ->setTo($to)
                ->setBody($message, 'text/html');
            if ($mailer->send($content)) {
                return true;
            } else {
                return false;
            }
        }catch (\Exception $e) {
           // dump($e->getMessage()); // Affiche l'erreur dans la page
            return false;
        }
    }
}