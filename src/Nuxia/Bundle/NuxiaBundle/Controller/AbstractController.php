<?php

namespace Nuxia\Bundle\NuxiaBundle\Controller;

use Nuxia\Component\Security\SecurityManagerInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\HttpKernel\HttpKernelInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\Router;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Symfony\Component\Templating\EngineInterface;

/**
 * Base class for every service controller
 * Most of shortcuts methods are similar to the base FrameworkBundle Controller
 *
 * @author Yannick Snobbert <yannick.snobbert@gmail.com>
 */
abstract class AbstractController
{
    /**
     * @var EngineInterface
     */
    protected $templating;

    /**
     * @var Router
     */
    protected $router;

    /**
     * @var SecurityManagerInterface
     */
    protected $security;

    /**
     * @var HttpKernelInterface
     */
    protected $httpKernel;

    /**
     * @var FlashBagInterface
     */
    protected $flashBag;

    /**
     * @param EngineInterface $templating
     */
    public function setTemplating(EngineInterface $templating)
    {
        $this->templating = $templating;
    }

    /**
     * @param RouterInterface $router
     */
    public function setRouter(RouterInterface $router)
    {
        $this->router = $router;
    }

    /**
     * @param SecurityManagerInterface $security
     */
    public function setSecurityManager(SecurityManagerInterface $security)
    {
        $this->security = $security;
    }

    /**
     * @param HttpKernelInterface $httpKernel
     */
    public function setHttpKernel(HttpKernelInterface $httpKernel)
    {
        $this->httpKernel = $httpKernel;
    }

    /**
     * @param FlashBagInterface $flashBag
     */
    public function setFlashBag(FlashBagInterface $flashBag = null)
    {
        $this->flashBag = $flashBag;
    }

    /**
     * @param  array|ControllerBagInterface $controllerBag
     *
     * @return ControllerBagInterface
     *
     * @throws \InvalidArgumentException
     */
    protected function initControllerBag($controllerBag = array())
    {
        if (is_array($controllerBag)) {
            $controllerBag = new ControllerBag($controllerBag, false);
        }
        if (!$controllerBag instanceof ControllerBagInterface) {
            throw new \InvalidArgumentException('$controllerBag must be an instance of ControllerBagInterface');
        }
        return $controllerBag;
    }

    /**
     * Gets the previous url
     *
     * The default route and default parameters avoid :
     * - 404 if the referrer is null
     * - infinite loop if the referrer and the current uri are the same
     *
     * @param  Request $request
     * @param  string  $defaultRoute
     * @param  array   $defaultParameters
     *
     * @return array|string
     */
    protected function getReferer(Request $request, $defaultRoute, array $defaultParameters = array())
    {
        $referer = $request->get('referer');
        if ($referer !== null && $referer !== $request->getUri()) {
            return $referer;
        }
        return $this->generateUrl($defaultRoute, $defaultParameters);
    }

    /**
     * Redirects to the previous url
     *
     * @param  Request $request
     * @param  string  $defaultRoute
     * @param  array   $defaultParameters
     * @param  int     $status
     *
     * @return RedirectResponse
     */
    protected function redirectToReferer(Request $request, $defaultRoute, array $defaultParameters = array(), $status = 302)
    {
        return $this->redirect($this->getReferer($request, $defaultRoute, $defaultParameters));
    }

    /**
     * Forwards the request to another controller.
     *
     * @param  Request $request    The current request
     * @param  string  $controller The controller name
     * @param  array   $path       An array of path parameters
     * @param  array  $ query      An array of query parameters
     *
     * @return Response A Response instance
     */
    public function forward(Request $request, $controller, array $path = array(), array $query = array())
    {
        $path['_controller'] = $controller;
        return $this->httpKernel->handle($request->duplicate($query, null, $path), HttpKernelInterface::SUB_REQUEST);
    }

    /**
     * Returns a RedirectResponse to the given URL.
     *
     * @param  string $url    The URL to redirect to
     * @param  int    $status The status code to use for the Response
     *
     * @return RedirectResponse
     */
    protected function redirect($url, $status = 302)
    {
        return new RedirectResponse($url, $status);
    }

    /**
     * Returns a RedirectResponse to the given route with the given parameters.
     *
     * @param  string $route      The name of the route
     * @param  array  $parameters An array of parameters
     * @param  int    $status     The status code to use for the Response
     *
     * @return RedirectResponse
     */
    protected function redirectToRoute($route, array $parameters = array(), $status = 302)
    {
        return $this->redirect($this->generateUrl($route, $parameters), $status);
    }

    /**
     * Adds a flash message to the current session for type.
     *
     * @param  string $type    The type
     * @param  string $message The message
     *
     * @throws \LogicException
     */
    protected function addFlash($type, $message)
    {
        if ($this->flashBag === null) {
            throw new \LogicException('You can not use the addFlash method if sessions are disabled.');
        }

        $this->flashBag->add($type, $message);
    }

    /**
     * Renders a view.
     *
     * @param  string   $view       The view name
     * @param  array    $parameters An array of parameters to pass to the view
     * @param  Response $response   A response instance
     *
     * @return Response A Response instance
     */
    protected function render($view, array $parameters = array(), Response $response = null)
    {
        return $this->templating->renderResponse($view, $parameters, $response);
    }

    /**
     * Returns a NotFoundHttpException.
     *
     * This will result in a 404 response code. Usage example:
     *
     *     throw $this->createNotFoundException('Page not found!');
     *
     * @param  string          $message  A message
     * @param  \Exception|null $previous The previous exception
     *
     * @return NotFoundHttpException
     */
    protected function createNotFoundException($message = 'Not Found', \Exception $previous = null)
    {
        return new NotFoundHttpException($message, $previous);
    }

    /**
     * Returns an AccessDeniedException.
     *
     * This will result in a 403 response code. Usage example:
     *
     *     throw $this->createAccessDeniedException('Unable to access this page!');
     *
     * @param  string          $message  A message
     * @param  \Exception|null $previous The previous exception
     *
     * @return AccessDeniedException
     */
    protected function createAccessDeniedException($message = 'Access Denied', \Exception $previous = null)
    {
        return new AccessDeniedException($message, $previous);
    }

    /**
     * Generates a URL from the given parameters.
     *
     * @param  string      $route         The name of the route
     * @param  mixed       $parameters    An array of parameters
     * @param  bool|string $referenceType The type of reference (one of the constants in UrlGeneratorInterface)
     *
     * @return string The generated URL
     *
     * @see UrlGeneratorInterface
     */
    protected function generateUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->router->generate($route, $parameters, $referenceType);
    }
}
