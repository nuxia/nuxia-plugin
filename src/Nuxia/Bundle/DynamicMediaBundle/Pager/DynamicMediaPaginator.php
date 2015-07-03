<?php

namespace Nuxia\Bundle\DynamicMediaBundle\Pager;

use BaseMold\CoreBundle\Pager\MediaPaginator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\ParameterBag;

class DynamicMediaPaginator extends MediaPaginator
{
    private function isFirstCall(ParameterBag $query, FormInterface $formFilter)
    {
        return $query->has($this->defaultOptions['pageParameterName']) || $query->has($formFilter->getName());
    }

    public function getTemplate($bundle, FormInterface $formFilter)
    {
        $template = $bundle . ':Default:';
        if ($this->isFirstCall($this->request->query, $formFilter)) {
            $template .= 'pagination';
        } else {
            $template .= 'list';
        }
        return $template . '.html.twig';
    }

    public function createPaginator(array $criteria)
    {
        return parent::createPaginator($criteria, 'admin');
    }
}
