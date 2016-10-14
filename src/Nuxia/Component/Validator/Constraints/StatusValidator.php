<?php

namespace Nuxia\Component\Validator\Constraints;

use Nuxia\Component\Parser;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class StatusValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value->getStatus() === $constraint->status) {
            foreach ($constraint->required_fields as $field) {
                $getter = 'get' . Parser::camelize($field);
                if (!$value->$getter()) {
                    $this->context->buildViolation($this->getConstraintMessage($value, $field, $constraint))
                        ->atPath($field)
                        ->addViolation();
                }
            }
        }
    }

    /**
     * @param mixed      $value
     * @param string     $field
     * @param Constraint $constraint
     *
     * @return string
     */
    private function getConstraintMessage($value, $field, Constraint $constraint)
    {
        if ($constraint->message !== null) {
            return $constraint->message;
        }

        return $value->getModelname() . '.' . $field . '.required.' . $constraint->status;
    }
}
