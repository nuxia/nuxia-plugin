<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Slug extends Constraint
{
    /**
     * @var string
     */
    public $message = 'slug.invalid';

    /**
     * {@inheritdoc}
     */
    public function getDefaultOption()
    {
        return ['message'];
    }
}
