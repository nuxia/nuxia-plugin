<?php

namespace Nuxia\Component\Console;

use Symfony\Component\Console\Question\Question;

abstract class AbstractInteractiveCommand extends AbstractCommand
{
    /**
     * @var string
     */
    const QUESTION_FORMAT = '<info>%s</info> ';

    /**
     * @var string
     */
    const DEFAULT_QUESTION_FORMAT = '[<comment>%s</comment>]';

    /**
     * @param $question
     * @param null $default
     * @return Question
     */
    public function createQuestion($question, $default = null)
    {
        $question = sprintf(self::QUESTION_FORMAT, $question);
        if ($default !== null) {
            $question .= sprintf(self::DEFAULT_QUESTION_FORMAT, $default);
        }
        return new Question($question, $default);
    }
}