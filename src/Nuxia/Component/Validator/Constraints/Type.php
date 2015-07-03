<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraints\Type as SymfonyType;

class Type extends SymfonyType
{
    /**
     * {@inheritdoc}
     */
    public function __construct($options = null)
    {
        $this->message = 'field.integer.invalid';
        parent::__construct($options);
    }
}
