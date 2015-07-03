<?php

namespace Nuxia\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ButtonTypeInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ResetType extends AbstractType implements ButtonTypeInterface
{
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['url'] = $form->getParent()->getConfig()->getAction();
    }

    public function getName()
    {
        return 'nuxia_reset';
    }

    public function getParent()
    {
        return 'reset';
    }
}