<?php

namespace Nuxia\Component\Doctrine\EventListener;

use Nuxia\Component\Security\SecurityManagerInterface;
use Symfony\Component\DependencyInjection\Exception\ServiceNotFoundException;

abstract class AbstractLogListener
{
    /**
     * @var SecurityManagerInterface
     */
    protected $securityManager;

    /**
     * @param SecurityManagerInterface $securityManager
     */
    public function setSecurityManager(SecurityManagerInterface $securityManager = null)
    {
        $this->securityManager = $securityManager;
    }

    /**
     * @param $user
     *
     * @return mixed
     * @throws ServiceNotFoundException
     */
    protected function getDefautUser($user)
    {
        if ($this->securityManager === null) {
            throw new ServiceNotFoundException('nuxia.security.manager', 'nuxia.log.listener.abstract');
        }
        if ($this->securityManager->getUser() !== null) {
            return $this->securityManager->getUser();
        }

        return $user;
    }

}
