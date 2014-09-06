<?php

namespace MamuzBlogFeed\Feed\Writer;

interface FactoryInterface
{
    /**
     * @param array                   $feedOptions
     * @param \IteratorAggregate|null $entries
     * @return \Zend\Feed\Writer\Feed
     */
    public function create(array $feedOptions, \IteratorAggregate $entries = null);
}
