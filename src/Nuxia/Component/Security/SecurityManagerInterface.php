<?php

namespace Nuxia\Component\Security;

use Symfony\Component\Security\Core\User\UserInterface;

interface SecurityManagerInterface
{
    /**
     * @return UserInterface|null
     */
    public function getUser();
}
