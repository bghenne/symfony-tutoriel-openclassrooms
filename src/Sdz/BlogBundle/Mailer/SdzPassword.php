<?php
namespace Sdz\BlogBundle\Mailer;


class SdzPassword
{
    protected $mailer;
    protected $secret;

    public function __construct(\Swift_Mailer $mailer, $secret)
    {
        $this->mailer = $mailer;
        $this->secret = $secret;
    }

    public function sendPasswordByEmail($email)
    {
        $password = $this->generatePassword($email);
        $mail = $this->mailer->createMessage();

        /** @var $mail \Swift_Message */
        $mail->addTo($email)
            ->setSubject('Mon petit mail de test')
            ->setSender($email)
            ->setBody(sprintf('Votre nouveau mot de passe : %s', $password));

        $this->mailer->send($mail);
    }

    public function generatePassword($email)
    {
        return sha1($email . $this->secret);
    }
} 