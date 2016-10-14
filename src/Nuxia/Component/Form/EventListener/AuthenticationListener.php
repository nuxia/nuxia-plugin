<?php

namespace Nuxia\Component\Form\EventListener;

use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Core\SecurityContextInterface;

class AuthenticationListener
{
    protected $session;

    public function __construct(SessionInterface $session)
    {
        $this->session = $session;
    }

    public function handle(FormEvent $event)
    {
        $error = $this->session->get(SecurityContextInterface::AUTHENTICATION_ERROR);
        $this->session->remove(SecurityContextInterface::AUTHENTICATION_ERROR);
        if ($error) {
            $event->getForm()->addError(new FormError($error->getMessage()));
        }
        $event->setData(
            [
                '_username' => $this->session->get(SecurityContextInterface::LAST_USERNAME),
            ]
        );
    }
}
