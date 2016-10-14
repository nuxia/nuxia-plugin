<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class ReservedWords extends Constraint
{
    /**
     * @var array
     */
    public $words = [
        'user',
        'admin',
        'application',
        'dashboard',
        'null',
    ];

    /**
     * @var string
     */
    public $message = 'reserved_words.invalid';

    /**
     * {@inheritdoc}
     */
    public function validatedBy()
    {
        return 'nuxia.validator.reserved_words';
    }
}
