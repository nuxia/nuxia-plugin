<?php

namespace Nuxia\Component\Security;

use Symfony\Component\Security\Core\Authentication\Token\Storage\TokenStorageInterface;
use Symfony\Component\Security\Core\Authorization\AuthorizationCheckerInterface;
use Symfony\Component\Security\Core\User\UserInterface;

class BaseSecurityManager implements SecurityManagerInterface
{
    /**
     * @var AuthorizationCheckerInterface
     */
    protected $authorizationChecker;

    /**
     * @var TokenStorageInterface
     */
    protected $tokenStorage;

    /**
     * @param AuthorizationCheckerInterface $securityContext
     */
    public function setAuthorizationChecker(AuthorizationCheckerInterface $authorizationChecker)
    {
        $this->authorizationChecker = $authorizationChecker;
    }

    /**
     * @param TokenStorageInterface $tokenStorage
     */
    public function setTokenStorage(TokenStorageInterface $tokenStorage)
    {
        $this->tokenStorage = $tokenStorage;
    }

    /**
     * @return UserInterface|null
     */
    public function getUser()
    {
        if (!$this->tokenStorage->getToken()) {
            return;
        }
        $user = $this->tokenStorage->getToken()->getUser();
        if (!is_object($user)) {
            return;
        }

        return $user;
    }

    /**
     * @return bool
     */
    public function isLoggedIn()
    {
        return $this->authorizationChecker->isGranted('IS_AUTHENTICATED_REMEMBERED');
    }

    /**
     * @return bool
     */
    public function isAdmin()
    {
        return $this->authorizationChecker->isGranted('ROLE_ADMIN');
    }
}
