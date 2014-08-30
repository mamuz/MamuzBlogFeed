<?php

namespace MamuzBlogFeed\View\Helper;

use MamuzBlog\Entity\Post;
use MamuzBlog\View\Helper\AbstractHelper;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed as FeedWriter;

class Feed extends AbstractHelper
{
    /** @var Post[] */
    private $posts;

    /** @var FeedWriter */
    private $feedWriter;

    /** @var Entry */
    private $entryPrototype;

    /**
     * @param FeedWriter         $feedWriter
     * @param Entry              $entryPrototype
     * @param \IteratorAggregate $posts
     */
    public function __construct(
        FeedWriter $feedWriter,
        Entry $entryPrototype,
        \IteratorAggregate $posts
    ) {
        $this->posts = $posts;
        $this->entryPrototype = $entryPrototype;
        $this->feedWriter = $feedWriter;
    }

    /**
     * @return Entry
     */
    private function getEntryPrototype()
    {
        return clone $this->entryPrototype;
    }

    /**
     * @return FeedWriter
     */
    public function render()
    {
        foreach ($this->posts as $post) {
            $entry = $this->createEntryBy($post);
            $this->feedWriter->addEntry($entry);
        }

        $this->feedWriter->orderByDate();
        $this->feedWriter->export($this->feedWriter->getType());

        return $this->feedWriter;
    }

    /**
     * @param Post $post
     * @return Entry
     */
    private function createEntryBy(Post $post)
    {
        $entry = $this->getEntryPrototype();

        $entry->setId($this->getRenderer()->hashId($post->getId()));
        $entry->setTitle($post->getTitle());
        $entry->setLink($this->getRenderer()->permaLinkPost($post));
        $entry->setDescription($this->getRenderer()->markdown($post->getDescription()));
        $entry->setContent($this->getRenderer()->markdown($post->getContent()));
        $entry->setDateModified($post->getModifiedAt());
        $entry->setDateCreated($post->getCreatedAt());

        foreach ($post->getTags() as $tag) {
            $entry->addCategory(
                array(
                    'term'   => $tag->getName(),
                    'scheme' => $this->getRenderer()->permaLinkTag($tag->getName()),
                )
            );
        }

        return $entry;
    }
}
