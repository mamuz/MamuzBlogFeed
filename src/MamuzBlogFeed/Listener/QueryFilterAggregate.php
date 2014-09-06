<?php

namespace MamuzBlogFeed\Listener;

use Zend\EventManager\EventInterface;
use Zend\Filter\FilterInterface;

class QueryFilterAggregate extends AbstractAggregate
{
    /** @var FilterInterface */
    private $queryFilter;

    /**
     * @param FilterInterface $queryFilter
     */
    public function __construct(FilterInterface $queryFilter)
    {
        $this->queryFilter = $queryFilter;
    }

    public function onPaginationCreate(EventInterface $event)
    {
        $this->queryFilter->filter($event->getParam('query'));
    }
}
