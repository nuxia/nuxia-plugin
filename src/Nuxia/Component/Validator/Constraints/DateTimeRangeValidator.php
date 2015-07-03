<?php

namespace Nuxia\Component\Validator\Constraints;

use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

//@SYMFONY 2.6 : feature #11673 [Validator] Added date support to comparison constraints and Range (webmozart)
class DateTimeRangeValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if ($value) {
            $value = $this->getDatetimeValue($value);
            if ($constraint->after !== null) {
                if ($constraint->afterMessage === null) {
                    $constraint->afterMessage = $constraint->after === 'now' ? 'field.datetime.range.future' : 'field.datetime.range.after';
                }
                if (!$constraint->after instanceof \DateTime) {
                    $constraint->after = $this->getDatetimeValue($constraint->after, $constraint);
                }
                if (!($value > $constraint->after)) {
                    $this->context->buildViolation(
                        $constraint->afterMessage, array('%after%' => $constraint->after->format($constraint->format)))
                    ->addViolation();
                }
            }
            if ($constraint->before !== null) {
                if ($constraint->beforeMessage === null) {
                    $constraint->beforeMessage = $constraint->before === 'now' ? 'field.datetime.range.past' : 'field.datetime.range.before';
                }
                if (!$constraint->before instanceof \DateTime) {
                    $constraint->before = $this->getDatetimeValue($constraint->before, $constraint);
                }
                if (!($value < $constraint->before)) {
                    $this->context->addViolation(
                        $constraint->beforeMessage,
                        array(
                            '%before%' => $constraint->before->format($constraint->format)
                        )
                    );
                }
            }
        }
    }

    /**
     * @param mixed $value
     *
     * @return \Datetime
     */
    private function getDatetimeValue($value)
    {
        if (!$value instanceof \Datetime) {
            $value = new \Datetime($value);
        }
        return $value;
    }
}
