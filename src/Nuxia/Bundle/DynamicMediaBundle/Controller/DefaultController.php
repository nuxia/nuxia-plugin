<?php

namespace Nuxia\Bundle\DynamicMediaBundle\Controller;

use BaseMold\CoreBundle\Form\Handler\MediaFilterFormHandler;
use Nuxia\Bundle\DynamicMediaBundle\Pager\DynamicMediaPaginator;
use Nuxia\Bundle\NuxiaBundle\Controller\AbstractAjaxController;
use Symfony\Component\HttpFoundation\Request;

class DefaultController extends AbstractAjaxController
{
    protected $dynamicMediaPaginator;
    protected $mediaFilterFormHandler;

    public function setDynamicMediaPaginator(DynamicMediaPaginator $dynamicMediaPaginator)
    {
        $this->dynamicMediaPaginator = $dynamicMediaPaginator;
    }

    public function setMediaFilterFormHandler(MediaFilterFormHandler $mediaFilterFormHandler)
    {
        $this->mediaFilterFormHandler = $mediaFilterFormHandler;
    }

    public function listAction(Request $request)
    {
        $controllerBag = $this->initControllerBag();
        $this->checkAjaxAction($request);
        $filters = $this->mediaFilterFormHandler->process();
        $paginator = $this->dynamicMediaPaginator->createPaginator(array_merge(array('object' => 'media'), $filters));
        $filterForm = $this->mediaFilterFormHandler->getForm();
        $controllerBag->set('paginator', $paginator);
        $controllerBag->set('filter_form', $filterForm);
        return $this->render(
            $this->dynamicMediaPaginator->getTemplate('NuxiaDynamicMediaBundle', $filterForm),
            $controllerBag->getTemplateVars()
        );
    }
}
