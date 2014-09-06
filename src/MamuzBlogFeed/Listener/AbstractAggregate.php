<?php

namespace MamuzBlogFeed\Listener;

use MamuzBlog\EventManager\Event;
use Zend\EventManager\AbstractListenerAggregate;
use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;

abstract class AbstractAggregate extends AbstractListenerAggregate
{
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
     * @return \Doctrine\ORM\Query
     */
    protected function getQueryBy(EventInterface $event)
    {
        return $event->getParam('query');
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    abstract public function onPaginationCreate(EventInterface $event);
}
