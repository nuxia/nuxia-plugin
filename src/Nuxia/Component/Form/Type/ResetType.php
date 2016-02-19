<?php

namespace Nuxia\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\ButtonTypeInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;

class ResetType extends AbstractType implements ButtonTypeInterface
{
    /**
     * {@inheritDoc}
     */
    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        $view->vars['url'] = $form->getParent()->getConfig()->getAction();
    }

    /**
     * {@inheritDoc}
     */
    public function getBlockPrefix()
    {
        return 'nuxia_reset';
    }

    /**
     * {@inheritDoc}
     */
    public function getParent()
    {
        return \Symfony\Component\Form\Extension\Core\Type\ResetType::class;
    }
}