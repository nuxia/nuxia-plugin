<?php

namespace Nuxia\Bundle\NuxiaBundle\Twig;

/**
 * This extension adds additionnal form tools on twig templates :
 * - form_actions : display form actions (submit, reset..)
 * - form_help : display form help
 * - is_form_type_of : determine if a FormView inherits from a type.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class FormExtension extends \Twig_Extension implements \Twig_Extension_InitRuntimeInterface
{
    /**
     * {@inheritdoc}
     */
    public function getFunctions()
    {
        $functions[] = new \Twig_SimpleFunction('form_actions', null, ['node_class' => 'Symfony\Bridge\Twig\Node\RenderBlockNode', 'is_safe' => ['html']]);
        $functions[] = new \Twig_SimpleFunction('form_help', null, ['node_class' => 'Symfony\Bridge\Twig\Node\SearchAndRenderBlockNode', 'is_safe' => ['html']]);
        $functions[] = new \Twig_SimpleFunction('is_form_type_of', [$this, 'isFormTypeOf']);

        return $functions;
    }
}
