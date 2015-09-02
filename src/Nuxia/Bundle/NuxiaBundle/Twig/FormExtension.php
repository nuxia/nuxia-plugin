<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Symfony\Bridge\Twig\Extension\FormExtension as SymfonyFormExtension;

/**
 * This extension adds additionnal form tools on twig templates :
 * - form_actions : display form actions (submit, reset..)
 * - form_help : display form help
 * - is_form_type_of : determine if a FormView inherits from a type
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class FormExtension extends SymfonyFormExtension
{
    /**
     * {@inheritDoc}
     */
    public function getFunctions()
    {
        $functions = parent::getFunctions();
        $functions[] = new \Twig_SimpleFunction('form_actions', null, array('node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => array('html')));
        $functions[] = new \Twig_SimpleFunction('form_help', null, array('node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => array('html')));
        $functions[] = new \Twig_SimpleFunction('is_form_type_of', array($this, 'isFormTypeOf'));

        return $functions;
    }
}
