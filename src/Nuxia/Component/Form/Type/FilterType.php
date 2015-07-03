<?php

namespace Nuxia\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FilterType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            array(
                'csrf_protection' => false,
                'process_data' => true,
                'method' => 'GET',
                'translation_domain' => 'form',
                'reset_button' => false,
            )
        );
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::PRE_SET_DATA, array($this, 'addFields'));
        if ($options['process_data'] === true) {
            $builder->addEventListener(FormEvents::SUBMIT, array($this, 'processSubmit'));
        }
    }

    public function addFields(FormEvent $event)
    {
        $form = $event->getForm();
        $form->add('submit', 'submit');
        if ($form->getConfig()->getOption('reset_button') === true) {
            $form->add('reset', 'nuxia_reset');
        }
    }

    public function processSubmit(FormEvent $event)
    {
        $form = $event->getForm();
        $data = array_filter($event->getData());

        foreach ($data as $child => $value) {

            //Prevent removed field
            if(!$form->has($child)){
                continue;
            }

            if ($form->get($child)->getConfig()->getType()->getName() === 'text') {
                $data[$child] = '%' . $data[$child] . '%';
            }
        }
        $event->setData($data);
    }

    public function buildView(FormView $view, FormInterface $form, array $options)
    {
        //@TODO a renommer
        $view->vars['display_filter'] = $form->isValid() && count(array_filter($form->getViewData())) > 0;
    }

    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children['submit']->vars['label'] = 'form.filter.submit.label';
        $view->children['submit']->vars['render_as_action'] = true;
        if ($options['reset_button'] === true) {
            $view->children['reset']->vars['label'] = 'form.filter.reset.label';
            $view->children['reset']->vars['render_as_action'] = true;
        }
    }

    public function getName()
    {
        return 'nuxia_filter';
    }
}