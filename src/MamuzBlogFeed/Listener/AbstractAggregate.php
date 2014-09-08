<?php

namespace MamuzBlogFeed\Listener;

use Doctrine\ORM\Query;
use Doctrine\ORM\Query\Parameter;
use MamuzBlogFeed\EventManager\Event;
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
     * @return Query|null
     */
    protected function getPostQueryFrom(EventInterface $event)
    {
        $query = $event->getParam('query');

        if ($query->contains('MamuzBlog\Entity\Post')) {
            return $query;
        } else {
            return null;
        }
    }

    /**
     * @param Query $query
     * @return null|string
     */
    protected function getTagParamFrom(Query $query)
    {
        $tag = $query->getParameter('tag');
        if ($tag instanceof Parameter) {
            $tag = $tag->getValue();
        }

        return $tag;
    }

    /**
     * @param EventInterface $event
     * @return void
     */
    abstract public function onPaginationCreate(EventInterface $event);
}
