<?php

namespace Nuxia\Component\Security\Authentication;

use Symfony\Component\Security\Core\Authentication\Provider\DaoAuthenticationProvider as SymfonyDaoAuthenticationProvider;
use Symfony\Component\Security\Core\Authentication\Token\UsernamePasswordToken;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * Override of DaoAuthenticationProvider which allows you to login without typing password if disablePassword is true.
 * This is very useful while developing or testing the application.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class AuthenticationProvider extends SymfonyDaoAuthenticationProvider
{
    /**
     * @var bool
     */
    protected $disablePassword;

    /**
     * @param bool $disablePassword
     */
    public function setDisablePassword($disablePassword)
    {
        $this->disablePassword = $disablePassword;
    }

    /**
     * {@inheritdoc}
     */
    protected function checkAuthentication(UserInterface $user, UsernamePasswordToken $token)
    {
        if ($this->disablePassword) {
            return true;
        }
        parent::checkAuthentication($user, $token);
    }
}
