<?php

namespace App\Notification;

use App\Entity\Contact;
use Twig\Environment;

class ContactNotification {
    
    /**
     * @var \Swift_Mailer
     */
    private $mailer;
    
    /**
     * @var Environment
     */
    private $renderer;
    
    /**
     * @param \Swift_Mailer $mailer
     * @param \Twig\Environment $renderer
     */
    public function __construct(\Swift_Mailer $mailer, Environment $renderer)
    {
        $this->mailer = $mailer;
        $this->renderer = $renderer;
    }
    
    public function notify(Contact $contact) 
    {
        // On prépare le message
        $message = (new \Swift_Message('Notice : ' . $contact->getResource()->getTitle()))
        ->setFrom('noreplay@agence.fr')
        ->setTo('contact@agence.fr')
        ->setReplyTo($contact->getEmail())
        ->setBody(
            $this->renderer->render(
                'emails/contact.html.twig',
                ['contact' => $contact]
                ),
            'text/html'
            );
        // On envoi le message
        $this->mailer->send($message);
    }
    
}
