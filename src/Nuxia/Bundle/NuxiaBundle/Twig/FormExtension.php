<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

use Symfony\Bridge\Twig\Extension\FormExtension as SymfonyFormExtension;

class FormExtension extends SymfonyFormExtension
{
    public function getFunctions()
    {
        $functions = parent::getFunctions();
        $functions['form_actions'] = new \Twig_Function_Node('Symfony\Bridge\Twig\Node\RenderBlockNode', array('is_safe' => array('html')));
        $functions['form_help'] = new \Twig_Function_Node('Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', array('is_safe' => array('html')));
        $functions[] = new \Twig_SimpleFunction('is_form_type_of', array($this, 'isFormTypeOf'));

        return $functions;
    }
}
