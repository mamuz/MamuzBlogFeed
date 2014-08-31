<?php

namespace MamuzBlogFeed\Listener;

use MamuzBlog\EventManager\Event;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\Filter\FilterInterface;

class Aggregate extends AbstractListenerAggregate
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

    /**
     * @param EventManagerInterface $events
     * @return void
     */
    public function attach(EventManagerInterface $events)
    {
        $events->getSharedManager()->attach(
            Event::IDENTIFIER,
            Event::PRE_PAGINATION_CREATE,
            array($this, 'onPaginationCreate')
        );
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    public function onPaginationCreate(EventInterface $event)
    {
        $this->queryFilter->filter($event->getParam('query'));
    }
}
