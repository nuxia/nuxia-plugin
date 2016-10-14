<?php

namespace Nuxia\Bundle\NuxiaBundle\Controller;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

abstract class AbstractAjaxController extends AbstractController
{
    /**
     * @var array
     */
    protected $trustedProxies;

    /**
     * @param $trustedProxies
     */
    public function setTrustedProxies($trustedProxies)
    {
        $this->trustedProxies = $trustedProxies;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    protected function checkAjaxAction(Request $request)
    {
        //@TODO ajouter le test sur l'ip local ($request->getClientIp()
        if (!$request->isXmlHttpRequest()) {
            return new Response('This url is only accessible via ajax or via local ip');
        }
    }
}
