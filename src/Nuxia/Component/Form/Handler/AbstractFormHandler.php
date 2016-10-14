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
     * @var FormFactoryInterface
     */
    protected $formFactory;

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
     * @param FormFactory $formFactory
     */
    public function setFormFactory(FormFactory $formFactory)
    {
        $this->formFactory = $formFactory;
    }

    /**
     * @param null  $data
     * @param array $options
     */
    protected function initForm($data = null, array $options = [])
    {
        $this->form = $this->formFactory->createForm($data, $options);
    }

    /**
     * @return mixed
     */
    protected function handleGetRequest()
    {
        return $this->handleRequest('GET');
    }

    /**
     * @return mixed
     */
    protected function handlePostRequest()
    {
        return $this->handleRequest('POST');
    }

    /**
     * @param string $method
     *
     * @DEPRECATED Utiliser handle(Post|Get)Request à la place
     * @REFACTOR Passer en private et enlever le défault (POST)
     *
     * @return bool
     */
    protected function handleRequest($method = 'POST')
    {
        $requestBag = $method === 'GET' ? $this->request->query : $this->request->request;
        //@REWORK Refactoring Johnatan => FormFilterHandler à l'extérieur des FormHandler
        $form = $method === 'GET' && $this->form === null ? $this->formFilter : $this->form;

        $form->handleRequest($this->request);

        return $form->isValid();
    }

    /**
     * @return FormInterface
     */
    public function getForm()
    {
        return $this->form;
    }
}
