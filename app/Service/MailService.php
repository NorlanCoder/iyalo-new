<?php

namespace App\Service;

use App\Mail\NotificationMail;
use Illuminate\Support\Facades\Mail;


class MailService{

    private $mailer;
    private $adminMail;

    public function __construct(Mail $mailer)
    {
        $this->mailer = $mailer;
        $this->adminMail = config('mail.from.address');
    }

    public function activationMail($token, $email){
        $message = "The activation token is : ".$token;
        $this->mailer::send('emails.token', [
            'data' => $message
        ], function($message)use ($email){
            $message->from( $this->adminMail, 'Bienvenu');
            $message->to($email)->subject('Activation du compte');
        });
    }

    public function activationCompte($url, $email){
        $message = 'Bienvenu chez nous. <br>Veuillez <a href="'.$url.'">cliquer ici</a> pour activer notre compte.';
        $this->mailer::send('emails.token', [
            'data' => $message
        ], function($message)use ($email){
            $message->from( $this->adminMail, 'Bienvenu');
            $message->to($email)->subject('Activation du compte');
        });
    }

    public function passwordMail($url, $email){
        $message = 'Veuillez <a href="'.$url.'">cliquer ici</a> pour réinitialiser votre mot passe.';
        $this->mailer::send('emails.token', [
            'data' => $message
        ], function($message)use ($email){
            $message->from( $this->adminMail, 'Mot de passe oublié');
            $message->to($email)->subject('Réinitialisation de mot de passe');
        });
    }

    public function contactMail(String $sender = null, String $receiver = null, String $header, String $message, String $object)
    {
        if ($sender == null && $receiver) {
            $sender = $this->adminMail;
        }elseif ($sender && $receiver == null) {
            $receiver = $this->adminMail;
        }elseif($sender == null && $receiver == null) {
            throw new \Exception("Must have a sender or a receiver", 1);
        }
        
        $this->mailer::send('emails.contact', [ 'data' => $message ], function($mail) use($sender, $receiver, $header, $object) {
            $mail->from($sender, $header);
            $mail->to($receiver)->subject($object);
        });
    }
}