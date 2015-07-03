<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\PropertyAccess\PropertyAccess;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

class DateTimeComparatorValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ((is_array($value) && count($value) === 0) || $value === null) {
            return;
        }
        if (!$this->getFieldValue($value, $constraint->field) || !$this->getFieldValue(
                $value,
                $constraint->comparedField
            )
        ) {
            return;
        }
        $fieldValue = $this->getDatetimeValue($value, $constraint->field);
        $comparedFieldValue = $this->getDatetimeValue($value, $constraint->comparedField);
        $messageParameters = array('%' . $constraint->type . '%' => $comparedFieldValue->format($constraint->format));
        switch ($constraint->type) {
            case 'after':
                if ($fieldValue < $comparedFieldValue) {
                    $this->context->buildViolation($constraint->message)
                        ->atPath($constraint->field)
                        ->setParameter('%' . $constraint->type . '%', $comparedFieldValue->format($constraint->format))
                        ->addViolation();
                }
                break;
            case 'before':
                if ($fieldValue > $comparedFieldValue) {
                    $this->context->buildViolation($constraint->message)
                        ->atPath($constraint->field)
                        ->setParameter('%' . $constraint->type . '%', $comparedFieldValue->format($constraint->format))
                        ->addViolation();
                }
                break;
        }
    }

    /**
     * @param mixed  $value
     * @param string $field
     *
     * @return mixed
     */
    private function getFieldValue($value, $field)
    {
        $accessor = PropertyAccess::createPropertyAccessor();
        $field = is_array($value) ? sprintf('[%s]', $field) : $field;

        return $accessor->getValue($value, $field);
    }

    /**
     * @param mixed  $value
     * @param string $field
     *
     * @return \Datetime|mixed
     */
    private function getDatetimeValue($value, $field)
    {
        $value = $this->getFieldValue($value, $field);
        if (!$value instanceof \Datetime) {
            $value = new \Datetime($value);
        }

        return $value;
    }
}
