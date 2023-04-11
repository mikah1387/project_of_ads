<?php

namespace App\Services;

use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;

class SendMailService
{

 private $mailer;


 public function __construct(MailerInterface $intermailer)
 {

    $this->mailer = $intermailer;


 }


 public function sendMail($from, $to, $subject, $template,$context = [] )
 {

    $email = (new TemplatedEmail())
    ->from($from)
    ->to($to)
    ->subject($subject)
    ->htmlTemplate($template)

// pass variables (name => value) to the template
     ->context($context);
    $this->mailer->send($email);

 }





}
