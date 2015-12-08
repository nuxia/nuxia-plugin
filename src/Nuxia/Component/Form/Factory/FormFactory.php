<?php

namespace Nuxia\Component\Form\Factory;

use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormTypeInterface;

class FormFactory
{
    /**
     * @var FormFactoryInterface
     */
    private $formFactory;

    /**
     * @var string|FormTypeInterface
     */
    private $type;

    /**
     * @var array
     */
    private $options;

    /**
     * @param FormFactoryInterface     $formFactory
     * @param string|FormTypeInterface $type
     * @param array                    $options
     */
    public function __construct(FormFactoryInterface $formFactory, $type, array $options = array())
    {
        $this->formFactory = $formFactory;
        $this->type = $type;
        $this->options = $options;
    }

    /**
     * @param array $options
     */
    public function setOptions(array $options)
    {
        $this->options = $options;
    }

    /**
     * @param mixed $data
     */
    public function setData($data)
    {
        $this->options['data'] = $data;
    }

    /**
     * @param  mixed|null $data
     * @param  array      $options
     *
     * @return \Symfony\Component\Form\FormInterface
     */
    public function createForm($data = null, array $options = array())
    {
        if ($data != null) {
            $this->setData($data);
        }
        return $this->formFactory->create($this->type, null, array_merge($this->options, $options));
    }
}
