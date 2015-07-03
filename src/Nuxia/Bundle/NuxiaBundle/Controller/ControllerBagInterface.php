<?php

namespace Nuxia\Bundle\NuxiaBundle\Controller;

use Nuxia\Component\Config\ParameterBagInterface;
use Symfony\Component\HttpFoundation\Request;

/**
 * ControllerBagInterface
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
interface ControllerBagInterface extends ParameterBagInterface
{
    /**
     * Return the parameters after applied callbacks required for templates :
     * - Convert \Symfony\Form\Form to \Symfony\Form\FormView
     *
     * @return array
     */
    public function getTemplateVars();

    /**
     * Add the parameters existing on the Request attributes bag.
     * This methods is useful on embedded controllers.
     *
     * @param  Request $request
     * @param  array   $parameters
     *
     * @return ControllerBagInterface
     */
    public function addFromRequestAttributes(Request $request, array $parameters);
}