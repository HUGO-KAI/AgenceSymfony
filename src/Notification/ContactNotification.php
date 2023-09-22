<?php

namespace App\Notification;

use App\Entity\Contact;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Twig\Environment;

class ContactNotification
{
    public function __construct(MailerInterface $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;

    }
    public function notify(Contact $contact):void
    {
        $message = (new TemplatedEmail())
            ->from('noreplay@agence.fr')
            ->to('contact@agence.fr')
            ->replyTo($contact->getEmail())
            ->subject($contact->getProperty()->getTitle())
            ->htmlTemplate('email/contact.html.twig')
            ->context([
                'contact' => $contact
            ])
        ;
        $this->mailer->send($message);

    }
}