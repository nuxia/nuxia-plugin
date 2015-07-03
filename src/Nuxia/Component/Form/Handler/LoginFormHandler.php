<?php

namespace Nuxia\Component\Form\Handler;

use Nuxia\Component\Form\EventListener\AuthenticationListener;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Security\Http\FirewallMapInterface;

class LoginFormHandler extends AbstractFormHandler
{
    protected $session;
    protected $firewallMap;

    public function __construct(SessionInterface $session, FirewallMapInterface $firewall)
    {
        $this->session = $session;
        $this->firewallMap = $firewalMap;
    }

    public function process()
    {
    }

    protected function onSuccess()
    {
        return true;
    }

    private function getFormOptions()
    {
        $firewall = array_keys($this->firewallMap->getListeners($this->request));
        print_r($firewall);
        return array(
            'pre_set_data_listener' => array(new AuthenticationListener($session), 'handle'),
        );
    }
}
