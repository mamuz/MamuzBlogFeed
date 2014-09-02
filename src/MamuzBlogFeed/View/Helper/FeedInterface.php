<?php

namespace MamuzBlogFeed\View\Helper;

interface FeedInterface
{
    /**
     * @return \Zend\Feed\Writer\Feed
     */
    public function render();
}
