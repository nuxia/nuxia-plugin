<?php

namespace Nuxia\Component\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\Form\FormView;
use Symfony\Component\OptionsResolver\OptionsResolver;

class MediaDataType extends AbstractType
{
    /**
     * {@inheritdoc}
     */
    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults(
            [
                'compound' => false,
                'mapped' => false,
                'required' => false,
                //@SYMFONY 2.3 Sera possible avec l'option auto_initialize à false
                //'data_class' => 'Nuxia\Component\Media\AbstractMedia',
            ]
        );
    }

    /**
     * {@inheritdoc}
     */
    public function finishView(FormView $view, FormInterface $form, array $options)
    {
        //@TODO Dans le cas d'une erreur cette ligne est obligatoire pour visualiser le media
        $view->vars['data'] = $options['data'];
        $view->vars['attr']['readonly'] = true;
    }
}
