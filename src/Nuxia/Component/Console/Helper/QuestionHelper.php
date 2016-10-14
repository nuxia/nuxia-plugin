<?php

namespace Nuxia\Component\Console\Helper;

use Symfony\Component\Console\Helper\QuestionHelper as SymfonyQuestionHelper;
use Symfony\Component\Console\Question\Question;

//@DEPRECATED : Utiliser AbstractInteractiveCommand
class QuestionHelper extends SymfonyQuestionHelper
{
    const QUESTION_FORMAT = '<info>%s</info> ';
    const DEFAULT_FORMAT = '[<comment>%s</comment>]';

    /**
     * @param $question
     * @param null $default
     *
     * @return Question
     */
    public function createQuestion($question, $default = null)
    {
        $question = sprintf(self::QUESTION_FORMAT, $question);
        if ($default !== null) {
            $question .= sprintf(self::DEFAULT_FORMAT, $default);
        }

        return new Question($question, $default);
    }
}
