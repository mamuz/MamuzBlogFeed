<?php

namespace MamuzBlogFeed\Controller\Plugin;

use Zend\Feed\Writer\Feed;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;
use Zend\View\Helper\HeadLink;

class HeadFeed extends AbstractPlugin
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
     * @param Feed $feed
     * @return void
     */
    public function add(Feed $feed)
    {
        $this->headLink->appendAlternate(
            $feed->getLink(),
            "application/" . $feed->getType() . "+xml",
            $feed->getTitle()
        );
    }
}
