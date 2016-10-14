<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

class DateTimeComparator extends Constraint
{
    /**
     * @var string
     */
    public $format = 'd/m/Y';

    /**
     * @var string
     */
    public $field;

    /**
     * @var string
     */
    public $comparedField;

    /**
     * @var string
     */
    public $type = 'after';

    /**
     * @var string
     */
    public $message;

    /**
     * {@inheritdoc}
     */
    public function getTargets()
    {
        return [
            self::CLASS_CONSTRAINT,
        ];
    }
}
