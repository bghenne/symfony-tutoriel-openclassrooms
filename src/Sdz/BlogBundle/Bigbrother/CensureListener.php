<?php

namespace Sdz\BlogBundle\Bigbrother;
use Symfony\Component\Security\Core\User\UserInterface;

class CensureListener
{
    protected $liste;
    protected $mailer;

    public function __construct(array $liste, \Swift_Mailer $mailer)
    {
        $this->liste = $liste;
        $this->mailer = $mailer;
    }

    protected function sendEmail($message, UserInterface $user)
    {
        $message = \Swift_Message::newInstance()
            ->setTo('benjamin.ghenne@gmail.com')
            ->setFrom('benjamin.ghenne@gmail.com')
            ->setSubject('Nouveau message censuré')
            ->setBody(sprintf('L\'utilisateur surveillé %s a posté le message suivant', $message));

        $this->mailer->send($message);
    }

    protected function censureMessage($message)
    {
        // Ici, totalement arbitraire :
        $message = str_replace(array('top secret', 'mot interdit'), '', $message);

        return $message;
    }

    public function onMessagePost(MessagePostEvent $event)
    {
        if (in_array($event->getUser()->getId(), $this->liste)) {
            // On envoie un e-mail à l'administrateur
            $this->sendEmail($event->getMessage(), $event->getUser());

            // On censure le message
            $message = $this->censureMessage($event->getMessage());
            // On enregistre le message censuré dans l'event
            $event->setMessage($message);
        }
    }
} 