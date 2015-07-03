<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class Status extends Constraint
{
    /**
     * @var array
     */
    public $required_fields = array();

    /**
     * @var string
     */
    public $message;

    /**
     * @var string
     */
    public $status;

    /**
     * {@inheritdoc}
     */
    public function getRequiredOptions()
    {
        return array('required_fields');
    }

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return self::CLASS_CONSTRAINT;
    }
}
