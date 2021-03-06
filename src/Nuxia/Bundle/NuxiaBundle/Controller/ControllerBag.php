<?php

namespace Nuxia\Bundle\NuxiaBundle\Controller;

use Nuxia\Component\Config\ParameterBag;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\Request;

/**
 * Holds and manages controller parameters.
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
class ControllerBag extends ParameterBag implements ControllerBagInterface
{
    /**
     * {@inheritdoc}
     */
    public function getTemplateVars()
    {
        $this->parameters['_template_vars'] = [];
        foreach ($this->parameters as $key => $value) {
            if (is_object($value) && $value instanceof Form) {
                $value = $value->createView();
            }
            $this->parameters['_template_vars'][$key] = $value;
        }

        return $this->parameters['_template_vars'];
    }

    /**
     * {@inheritdoc}
     */
    public function addFromRequestAttributes(Request $request, array $parameters)
    {
        foreach ($parameters as $parameter) {
            if (!$this->has($parameter) && $request->attributes->has($parameter)) {
                $this->set($parameter, $request->attributes->get($parameter));
            }
        }

        return $this;
    }
}
