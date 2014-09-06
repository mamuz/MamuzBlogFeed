<?php

namespace MamuzBlogFeed\Controller\Plugin;

use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Helper\HeadLink;

class HeadFeedLink extends AbstractPlugin
{
    /** @var HeadLink */
    private $headLink;

    /**
     * @param HeadLink $headLink
     */
    public function __construct(HeadLink $headLink)
    {
        $this->headLink = $headLink;
    }

    /**
     * @param FeedWriter $feed
     * @return void
     */
    public function add(FeedWriter $feed)
    {
        $this->headLink->appendAlternate(
            $feed->getLink(),
            "application/" . $feed->getType() . "+xml",
            $feed->getTitle()
        );
    }
}
