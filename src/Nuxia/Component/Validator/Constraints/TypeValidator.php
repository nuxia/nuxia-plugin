<?php

namespace Nuxia\Component\Validator\Helper;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\Constraints\TypeValidator as SymfonyTypeValidator;

class TypeValidator extends SymfonyTypeValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (null === $value) {
            return;
        }
        if ($constraint->type === 'integer' || $constraint->type === 'int') {
           if (ValidatorUtil::isInteger($value)) {
               return;
           }
        }

        return parent::validate($value, $constraint);
    }
}
