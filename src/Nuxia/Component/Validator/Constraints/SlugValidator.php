<?php

namespace Nuxia\Component\Validator\Constraints;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * Ce validateur utilise la méthode urlize du bundle GedmoDoctrineExtension il doit donc être chargé.
 *
 * @author yannicksnobbert
 */
class SlugValidator extends ConstraintValidator
{
    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        if (empty($value) || $value !== Urlizer::urlize($value)) {
            $this->context->addViolation($constraint->message);
        }
    }
}
