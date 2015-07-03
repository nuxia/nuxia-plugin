<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

//@SYMFONY 2.6 : feature #11673 [Validator] Added date support to comparison constraints and Range (webmozart)
class DateTimeRange extends Constraint
{
    /**
     * @var string
     */
    public $format = 'd/m/Y';

    /**
     * @var
     */
    public $after = null;

    /**
     * @var
     */
    public $before = null;

    /**
     * @var string
     */
    public $afterMessage;

    /**
     * @var string
     */
    public $beforeMessage;
}
