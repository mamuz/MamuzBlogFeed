<?php

namespace MamuzBlogFeed\Listener;

use Doctrine\ORM\Query\Parameter;
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
        /** @var \Doctrine\ORM\Query $query */
        $query = $event->getParam('query');
        $tag = $query->getParameter('tag');
        if ($tag instanceof Parameter) {
            $tag = $tag->getValue();
        }
        $feedOptions = $this->configProvider->getFor($tag);

        if (isset($feedOptions['autoHeadLink']) && $feedOptions['autoHeadLink']) {
            $feed = $this->feedFactory->create($feedOptions);
            foreach ($feed->getFeedLinks() as $type => $link) {
                $this->headLink->appendAlternate(
                    $link,
                    "application/" . $type . "+xml",
                    $feed->getTitle()
                );
            }
        }
    }
}
