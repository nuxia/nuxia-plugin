<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Nuxia\Component\Security\SecurityManagerInterface;

/**
 * This extension gives you access to the security manager (nuxia.security.manager) from twig templates
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class SecurityExtension extends \Symfony\Bridge\Twig\Extension\SecurityExtension
{
    /**
     * @var SecurityManagerInterface
     */
    protected $securityManager;

    /**
     * @param SecurityManagerInterface $securityManager
     */
    public function setSecurityManager(SecurityManagerInterface $securityManager)
    {
        $this->securityManager = $securityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function getGlobals()
    {
        return array('security' => $this->securityManager);
    }
}
