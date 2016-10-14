<?php

namespace Nuxia\Component\Mailer;

use Symfony\Component\HttpFoundation\ParameterBag;

class Mail
{
    /**
     * @var ParameterBag
     */
    protected $addresses;

    /**
     * @var ParameterBag
     */
    protected $templateOptions;

    /**
     * @var Mailer
     */
    protected $mailer;

    /**
     * @param array $addresses
     * @param array $templateOptions
     */
    public function __construct(array $addresses = [], array $templateOptions = [])
    {
        $this->addresses = new ParameterBag($addresses);
        $this->templateOptions = new ParameterBag($templateOptions);
    }

    /**
     * @param Mailer $mailer
     */
    public function setMailer(Mailer $mailer)
    {
        $this->mailer = $mailer;
    }

    /**
     * @param \Swift_Message $switfMessage
     */
    private function setRecipients(\Swift_Message $switfMessage)
    {
        foreach ($this->addresses as $key => $address) {
            $setter = 'set' . ucfirst($key);
            $switfMessage->$setter($address);
        }
    }

    /**
     * @return array
     */
    private function getTemplateParameters()
    {
        if ($this->templateOptions->has('parameters')) {
            return $this->templateOptions->get('parameters')->all();
        }

        return [];
    }

    /**
     * @param string      $route
     * @param array       $parameters
     * @param null|string $name
     *
     * @throws \Exception
     */
    public function addLink($route, array $parameters = [], $name = null)
    {
        if ($this->mailer->getRouter() === null) {
            throw new \Exception('Router must be set to add link');
        }
        $link = $this->router->generate($route, $parameters, true);
        if ($name === null) {
            $name = $route . '_link';
        }
        $this->templateOptions->get('parameters')->set('name', $link);
    }

    /**
     * @param array $parameters
     */
    public function addTemplateParameters(array $parameters = [])
    {
        $this->templateOptions->get('parameters')->add($parameters);
    }

    /**
     * @param string $subject
     */
    public function setSubject($subject)
    {
        $this->templateOptions->set('subject', $subject);
    }

    /**
     * @return \Twig_TemplateInterface
     */
    private function loadTemplate()
    {
        $template = $this->templateOptions->get('template');
        $language = $this->templateOptions->get('parameters')->get('language', null);

        if ($language !== null) {
            $pos = strrpos($template, '/');

            //@DEPRECATED Tous les templates du mailer doivent utiliser la nouvelle notation des templates
            //$variable replace supprimée
            if ($pos === false) {
                $replace = ':';
                $pos = strrpos($template, ':');
            } else {
                $replace = '/';
            }

            $buffer = substr($template, $pos + 1);
            $template = str_replace($buffer, $language . $replace . $buffer, $template);
        }

        $template .= '.html.twig';

        return $this->mailer->getTwig()->loadTemplate($template);
    }

    /**
     * @return \Swift_Message
     */
    public function createSwitfMessage()
    {
        $template = $this->loadTemplate();
        $swiftMessage = \Swift_Message::newInstance();
        if (!$this->templateOptions->has('subject')) {
            $this->templateOptions->set('subject', $template->renderBlock('subject', $this->getTemplateParameters()));
        }
        $this->setRecipients($swiftMessage);
        $swiftMessage->setSubject($this->templateOptions->get('subject'));
        $swiftMessage->setBody($template->render($this->getTemplateParameters()), 'text/html');
        $swiftMessage->getHeaders()->addTextHeader('Content-language', $this->templateOptions->get('parameters')->get('language'));
        $swiftMessage->addPart($template->renderBlock('content_text', $this->getTemplateParameters()), 'text/plain');

        return $swiftMessage;
    }
}
