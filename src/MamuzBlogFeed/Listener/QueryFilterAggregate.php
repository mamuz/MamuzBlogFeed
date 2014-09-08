<?php

namespace MamuzBlogFeed\Listener;

use MamuzBlogFeed\Filter\AbstractTagParamAware as FilterInterface;
use Zend\EventManager\EventInterface;

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
        if (($query = $this->getPostQueryFrom($event)) === null) {
            return;
        }

        $tagParam = $this->getTagParamFrom($query);
        $this->queryFilter->setTagParam($tagParam)->filter($this->getPostQueryFrom($event));
    }
}
