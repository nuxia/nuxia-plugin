<?php

namespace Nuxia\Component\HttpFoundation\Session\Flash;

use Symfony\Component\HttpFoundation\Session\Flash\FlashBag as SymfonyFlashBag;

class FlashBag extends SymfonyFlashBag
{
    /**
     * {@inheritdoc}
     */
    public function add($type, $message, array $translationParameters = array())
    {
        parent::add($type, $this->parseMessage($message, true, $translationParameters));
    }

    /**
     * @param string $type
     * @param string $message
     * @param array  $translationParameters
     */
    public function addStatic($type, $message, array $translationParameters = array())
    {
        parent::add($type, $this->parseMessage($message, false, $translationParameters));
    }

    /**
     * @param string $message
     * @param string $autoClose
     * @param array  $translationParameters
     *
     * @return array
     */
    private function parseMessage($message, $autoClose, array $translationParameters = array())
    {
        return array(
            'message' => $message,
            'autoclose' => $autoClose,
            'translation_parameters' => $translationParameters
        );
    }
}
