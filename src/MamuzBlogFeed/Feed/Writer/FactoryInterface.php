<?php

namespace MamuzBlogFeed\Feed\Writer;

interface FactoryInterface
{
    /**
     * @param array               $feedOptions
     * @param \IteratorAggregate  $entries
     * @return \Zend\Feed\Writer\Feed
     */
    public function create(array $feedOptions, \IteratorAggregate $entries);
}
