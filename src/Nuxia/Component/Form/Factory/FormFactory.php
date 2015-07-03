<?php

namespace Nuxia\Component\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;

class FormFactory
{
    private $formFactory;
    private $type;
    private $options;

    public function __construct(FormFactoryInterface $formFactory, $type, array $options = array())
    {
        $this->formFactory = $formFactory;
        $this->type = $type;
        $this->options = $options;
    }

    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    public function setData($data)
    {
        $this->options['data'] = $data;
    }

    public function createForm($data = null, array $options = array())
    {
        if ($data != null) {
            $this->setData($data);
        }
        return $this->formFactory->create($this->type, null, array_merge($this->options, $options));
    }
}
