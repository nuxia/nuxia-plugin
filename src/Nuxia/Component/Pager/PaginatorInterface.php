<?php

namespace Nuxia\Component\Pager;

interface PaginatorInterface
{
    public function createPaginator(array $criteria = array(), $type);
}