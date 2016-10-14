<?php

namespace Nuxia\Component\Mailer;

use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\Routing\RouterInterface;

class Mailer
{
    /**
     * @var \Swift_Mailer
     */
    protected $mailer;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    /**
     * @var ParameterBag
     */
    protected $parameters;

    /**
     * @var RouterInterface
     */
    protected $router;

    /**
     * @param \Swift_Mailer     $mailer
     * @param \Twig_Environment $twig
     * @param array             $parameters
     */
    public function __construct(\Swift_Mailer $mailer, \Twig_Environment $twig, array $parameters = [])
    {
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->parameters = new ParameterBag($parameters);
    }

    /**
     * @param string|array $addresses
     * @param null|string  $template
     * @param array        $templateParameters
     *
     * @return Mail
     */
    public function createMail($addresses, $template = null, array $templateParameters = [])
    {
        $templateOptions = $this->parseTemplateOptions($template, $templateParameters);

        $mail = new Mail($this->parseAddresses($addresses), $templateOptions);
        $mail->setMailer($this);

        return $mail;
    }

    /**
     * @param \Swift_Message|Mail $mail
     */
    public function sendMail($mail)
    {
        if (!$mail instanceof \Swift_Message) {
            $mail = $mail->createSwitfMessage();
        }
        $this->mailer->send($mail);
    }

    /**
     * @param null|string $template
     * @param array       $templateParameters
     *
     * @return array
     */
    private function parseTemplateOptions($template, array $templateParameters)
    {
        if (!array_key_exists('language', $templateParameters) && $this->parameters->has('language')) {
            $templateParameters['language'] = $this->parameters->get('language');
        }

        return [
            'template' => $template === null ? $this->parameters->get('template') : $template,
            'parameters' => new ParameterBag($templateParameters),
        ];
    }

    /**
     * @param string|array $addresses
     *
     * @throws \Exception
     *
     * @return array
     */
    private function parseAddresses($addresses)
    {
        if (!is_array($addresses)) {
            $addresses = ['to' => $addresses];
        }
        if (!isset($addresses['to'])) {
            throw new \Exception('You must define a recipient with "to" key');
        }
        if (!isset($addresses['from'])) {
            $addresses['from'] = $this->parameters->get('from');
        }

        return $addresses;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param string $language
     */
    public function setDefaultLanguage($language)
    {
        $this->parameters->set('language', $language);
    }

    /**
     * @return RouterInterface
     */
    public function getRouter()
    {
        return $this->router;
    }

    /**
     * @return \Twig_Environment
     */
    public function getTwig()
    {
        return $this->twig;
    }
}
