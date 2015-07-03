<?php

namespace Nuxia\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormError;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\NotBlank;

//@TODO Utiliser la constrainte DatetimeComparator
class DateRangeType extends AbstractType
{
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(array(
            'csrf_protection' => false,
            'required' => true,
            'format' => 'dd/MM/yyyy',
            'error_bubbling' => false,
        ));
    }
    
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, array($this, 'process'));
        $contraints = $options['required'] ? array(new NotBlank(array('message' => 'field.required'))) : array();
        $builder->add('start', 'date', array(
            'format' => $options['format'],
            'widget' => 'single_text',
            'invalid_message' => 'field.date.invalid',
            'invalid_message_parameters' => array('%format%' => $options['format']),
            'input' => 'string',
            'constraints' => $contraints,
        ));
        $builder->add('end', 'date', array(
            'format' => $options['format'],
            'widget' => 'single_text',
            'invalid_message' => 'field.date.invalid',
            'invalid_message_parameters' =>  array('%format%' => $options['format']),
            'input' => 'string',
            'constraints' => $contraints,
        ));
    }
    
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children['start']->vars['label'] = 'date.range.start.label';
        $view->children['end']->vars['label'] = 'date.range.end.label';
    }
    
    public function process(FormEvent $event)
    {
        $form = $event->getForm();
        $data = $event->getData();
        //@TODO A supprimer quand la constrainte DatetimeComparator sera en place
        if (strtotime($data['start']) > strtotime($data['end'])) {
            $form->get('start')->addError(new FormError(null, 'date.range.invalid'));
        } else {
            $data['operator'] = 'between';
            $data['start'] = $data['start'] . ' 00:00:00';
            $data['end'] = $data['end'] . ' 23:59:59';
            $event->setData($data);
        }
    }
    
    public function getName()
    {
        return 'nuxia_date_range';
    }

    public function getParent()
    {
        return 'nuxia_form';
    }
}
