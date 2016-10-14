<?php

namespace Nuxia\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FormType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(['translation_domain' => 'form']);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, [$this, 'addFields']);
    }

    /**
     * {@inheritdoc}
     */
    public function addFields(FormEvent $event)
    {
        $form = $event->getForm();
        if ($form->isRoot()) {
            $form->add('submit', SubmitType::class);
        }
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        if ($form->isRoot()) {
            $view->vars['help'] = 'form.required_fields.help';
            $view->children['submit']->vars['label'] = 'form.submit.label';
            $view->children['submit']->vars['render_as_action'] = true;
        }
    }
}
