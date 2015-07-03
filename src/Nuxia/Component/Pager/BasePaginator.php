<?php

namespace Nuxia\Component\Pager;

use Knp\Component\Pager\Paginator as KnpPaginator;
use Symfony\Component\HttpFoundation\RequestStack;

class BasePaginator extends KnpPaginator
{
    protected $requestStack;
    protected $request;

    public function setRequestStack(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
        $this->request = $requestStack->getCurrentRequest();
    }

    protected function doPaginate($target, $limit = null)
    {
        //@NUXIA il ne faut pas utiliser à cause des perfs $this->request->get
        //Cette instruction sera remplacé par $this->request->query il faut bien penser à passer la query dans les embed controller
        return $this->paginate(
            $target,
            $this->request->get($this->defaultOptions['pageParameterName'], 1),
            $this->request->query->get('limit', $limit)
        );
    }
}
