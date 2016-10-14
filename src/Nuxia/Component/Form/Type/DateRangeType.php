<?php

namespace Nuxia\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
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
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'csrf_protection' => false,
            'required' => true,
            'format' => 'dd/MM/yyyy',
            'error_bubbling' => false,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->addEventListener(FormEvents::SUBMIT, [$this, 'process']);
        $contraints = $options['required'] ? [new NotBlank(['message' => 'field.required'])] : [];
        $builder->add('start', DateType::class, [
            'format' => $options['format'],
            'widget' => 'single_text',
            'invalid_message' => 'field.date.invalid',
            'invalid_message_parameters' => ['%format%' => $options['format']],
            'input' => 'string',
            'constraints' => $contraints,
        ]);
        $builder->add('end', DateType::class, [
            'format' => $options['format'],
            'widget' => 'single_text',
            'invalid_message' => 'field.date.invalid',
            'invalid_message_parameters' => ['%format%' => $options['format']],
            'input' => 'string',
            'constraints' => $contraints,
        ]);
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        $view->children['start']->vars['label'] = 'date.range.start.label';
        $view->children['end']->vars['label'] = 'date.range.end.label';
    }

    /**
     * @param FormEvent $event
     */
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

    /**
     * {@inheritdoc}
     */
    public function getParent()
    {
        return FormType::class;
    }
}
