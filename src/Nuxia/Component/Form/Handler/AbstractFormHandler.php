<?php

namespace Nuxia\Component\Form\Handler;

use Nuxia\Component\Form\Factory\FormFactory;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\RouterInterface;

abstract class AbstractFormHandler
{
    /**
     * @deprecated will be removed use symfony/formFactory instead
     * @var FormFactory
     */
    protected $legacyFormFactory;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var string
     */
    protected $formType;

    /**
     * @var RequestStack
     */
    protected $requestStack;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @var Request
     */
    protected $request;

    /**
     * @var FormInterface
     */
    protected $form;

    /**
     * @param RequestStack $requestStack
     */
    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getCurrentRequest();
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param FormFactoryInterface|FormFactory $formFactory
     */
    public function setFormFactory($formFactory)
    {
        $this->legacyFormFactory = $formFactory;
    }

    /**
     * @param FormFactoryInterface|FormFactory $formFactory
     */
    public function setSymfonyFormFactory($formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param string $formType
     */
    public function setFormType($formType)
    {
        $this->formType = $formType;
    }


    /**
     * @param mixed|null $data
     * @param array      $options
     */
    protected function initForm($data = null, array $options = [])
    {
        if (null !== $this->formType) {
            $this->form = $this->formFactory->create($this->formType, $data, $options);
        } else {
            $this->form = $this->legacyFormFactory->createForm($data, $options);
        }
    }

    /**
     * @return bool
     */
    protected function handleRequest()
    {
        $this->form->handleRequest($this->request);

        return $this->isFormValid();
    }

    /**
     * {@inheritdoc}
     */
    public function getForm()
    {
        return $this->form;
    }

    /**
     * {@inheritdoc}
     */
    public function isFormValid()
    {
        return $this->form->isSubmitted() && $this->form->isValid();
    }

}
