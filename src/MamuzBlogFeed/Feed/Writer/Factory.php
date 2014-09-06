<?php

namespace MamuzBlogFeed\Feed\Writer;

use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed;
use Zend\Stdlib\Extractor\ExtractionInterface;
use Zend\Stdlib\Hydrator\HydrationInterface;

class Factory implements FactoryInterface
{
    /** @var Feed */
    private $feed;

    /** @var \IteratorAggregate */
    private $entries;

    /** @var Entry */
    private $entryPrototype;

    /** @var ExtractionInterface */
    private $postExtractor;

    /** @var HydrationInterface */
    private $hydrator;

    /**
     * @param ExtractionInterface $postExtractor
     * @param HydrationInterface  $hydrator
     * @param Feed                $feed
     */
    public function __construct(
        ExtractionInterface $postExtractor,
        HydrationInterface $hydrator,
        Feed $feed = null
    ) {
        $this->postExtractor = $postExtractor;
        $this->hydrator = $hydrator;

        if ($feed instanceof Feed) {
            $this->feed = $feed;
        } else {
            $this->feed = new Feed;
        }
    }

    public function create(array $feedOptions, \IteratorAggregate $entries = null)
    {
        $this->createFeed($feedOptions);

        if ($entries) {
            $this->entries = $entries;
            $this->createEntryPrototype();
            $this->createEntries();
        }

        return $this->feed;
    }

    /**
     * @param array $feedOptions
     * @return void
     */
    private function createFeed(array $feedOptions)
    {
        $this->feed->setType('rss');
        $this->feed->setDateModified(time());
        $this->hydrator->hydrate(
            $feedOptions,
            $this->feed
        );
    }

    /**
     * @return void
     */
    private function createEntryPrototype()
    {
        $this->entryPrototype = $this->feed->createEntry();
        if ($copyright = $this->feed->getCopyright()) {
            $this->entryPrototype->setCopyright($copyright);
        }
        if ($authors = $this->feed->getAuthors()) {
            $this->entryPrototype->addAuthors($authors);
        }
    }

    /**
     * @return void
     */
    private function createEntries()
    {
        foreach ($this->entries as $post) {
            $entry = $this->hydrator->hydrate(
                $this->postExtractor->extract($post),
                $this->getEntryPrototype()
            );
            /** @var Entry $entry */
            $this->feed->addEntry($entry);
        }
    }

    /**
     * @return Entry
     */
    private function getEntryPrototype()
    {
        return clone $this->entryPrototype;
    }
}
