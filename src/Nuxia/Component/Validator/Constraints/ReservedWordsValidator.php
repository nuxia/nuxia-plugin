<?php

namespace Nuxia\Component\Validator\Constraints;

use Gedmo\Sluggable\Util\Urlizer;
use Symfony\Component\Validator\Constraint;
use Symfony\Component\Validator\ConstraintValidator;

/**
 * @author yannicksnobbert
 **/
class ReservedWordsValidator extends ConstraintValidator
{
    /**
     * @var array
     */
    private $additionnal_words;

    /**
     * @param array $words
     */
    public function __construct(array $words)
    {
        $this->additionnal_words = $words;
    }

    /**
     * {@inheritdoc}
     */
    public function validate($value, Constraint $constraint)
    {
        $reserved_words = array_merge($constraint->words, $this->additionnal_words);
        if (in_array(Urlizer::urlize($value), $reserved_words)) {
            $this->context->addViolation($constraint->message, array('%word%' => $value));
        }
    }
}
