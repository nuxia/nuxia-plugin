<?php

namespace Nuxia\Component\Form\Handler;

use Symfony\Component\Form\FormInterface;

interface FormHandlerInterface
{
    /**
     * @return FormInterface
     */
    public function getForm();
}
