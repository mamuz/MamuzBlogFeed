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

    /** @var Entry */
    private $entryPrototype;

    /** @var ExtractionInterface */
    private $postExtractor;

    /** @var HydrationInterface */
    private $hydrator;

    /** @var \DateTime|null */
    private $lastModifiedDate;

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
            $this->createEntries($entries);
            $this->assignDateModifiedToFeed();
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
        $this->feed->setLastBuildDate(time());

        $this->hydrator->hydrate(
            $feedOptions,
            $this->feed
        );

        if (isset($feedOptions['feedUrl'])) {
            $this->feed->setFeedLink($feedOptions['feedUrl'], $this->feed->getType());
        }
    }

    /**
     * @param \IteratorAggregate $entries
     * @return void
     */
    private function createEntries(\IteratorAggregate $entries)
    {
        foreach ($entries as $post) {
            $entry = $this->hydrator->hydrate(
                $this->postExtractor->extract($post),
                $this->getEntryPrototype()
            );
            /** @var Entry $entry */
            $this->feed->addEntry($entry);
            $this->calculateLastModifiedBy($entry->getDateModified());
        }
    }

    /**
     * @return Entry
     */
    private function createEntryPrototype()
    {
        $entryPrototype = $this->feed->createEntry();
        if ($copyright = $this->feed->getCopyright()) {
            $entryPrototype->setCopyright($copyright);
        }
        if ($authors = $this->feed->getAuthors()) {
            $entryPrototype->addAuthors($authors);
        }

        return $entryPrototype;
    }

    /**
     * @return Entry
     */
    private function getEntryPrototype()
    {
        if (!$this->entryPrototype instanceof Entry) {
            $this->entryPrototype = $this->createEntryPrototype();
        }

        return clone $this->entryPrototype;
    }

    /**
     * @param string|\DateTime $dateTime
     * @return void
     */
    private function calculateLastModifiedBy($dateTime)
    {
        if (!$dateTime instanceof \DateTime) {
            $dateTime = new \DateTime($dateTime);
        }

        if (!$this->lastModifiedDate instanceof \DateTime
            || $dateTime > $this->lastModifiedDate
        ) {
            $this->lastModifiedDate = $dateTime;
        }
    }

    /**
     * @return void
     */
    private function assignDateModifiedToFeed()
    {
        if (!$this->feed->getDateModified()) {
            $this->feed->setDateModified($this->lastModifiedDate);
        }
        $this->lastModifiedDate = null;
    }
}
