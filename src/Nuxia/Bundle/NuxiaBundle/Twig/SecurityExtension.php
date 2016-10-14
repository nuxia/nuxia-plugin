<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Nuxia\Component\Security\SecurityManagerInterface;
use Symfony\Bridge\Twig\Extension\SecurityExtension as SymfonySecurityExtension;

/**
 * This extension gives you access to the security manager (nuxia.security.manager) from twig templates.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class SecurityExtension extends SymfonySecurityExtension implements \Twig_Extension_GlobalsInterface
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
     * {@inheritdoc}
     */
    public function getGlobals()
    {
        return ['security' => $this->securityManager];
    }
}
