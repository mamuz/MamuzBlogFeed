<?php

namespace MamuzBlogFeed\Listener;

use MamuzBlogFeed\Feed\Writer\FactoryInterface;
use MamuzBlogFeed\Options\ConfigProviderInterface;
use Zend\EventManager\EventInterface;
use Zend\View\Helper\HeadLink;

class HeadLinkAggregate extends AbstractAggregate
{
    /** @var HeadLink */
    private $headLink;

    /** @var FactoryInterface */
    private $feedFactory;

    /** @var ConfigProviderInterface */
    private $configProvider;

    /**
     * @param HeadLink                $headLink
     * @param FactoryInterface        $feedFactory
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        HeadLink $headLink,
        FactoryInterface $feedFactory,
        ConfigProviderInterface $configProvider
    ) {
        $this->headLink = $headLink;
        $this->feedFactory = $feedFactory;
        $this->configProvider = $configProvider;
    }

    public function onPaginationCreate(EventInterface $event)
    {
        $tag = $event->getParam('query')->getParameter('tag');
        $feedOptions = $this->configProvider->getFor($tag);

        $feed = $this->feedFactory->create($feedOptions);

        $this->headLink->appendAlternate(
            $feed->getLink(),
            "application/" . $feed->getType() . "+xml",
            $feed->getTitle()
        );
    }
}