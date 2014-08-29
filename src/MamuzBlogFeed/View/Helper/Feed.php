<?php

namespace MamuzBlogFeed\View\Helper;

use MamuzBlog\View\Helper\AbstractHelper;
use Zend\Feed\Writer\Feed as FeedWriter;

class Feed extends AbstractHelper
{
    /** @var \MamuzBlog\Entity\Post[] */
    private $posts;

    /** @var FeedWriter */
    private $feedWriter;

    /** @var string */
    private $type = 'rss';

    /**
     * @param FeedWriter         $feedWriter
     * @param \IteratorAggregate $posts
     */
    public function __construct(FeedWriter $feedWriter, \IteratorAggregate $posts)
    {
        $this->posts = $posts;
        $this->feedWriter = $feedWriter;
    }

    /**
     * @param string $type
     * @return Feed
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * {@link render()}
     */
    public function __invoke()
    {
        return $this->render();
    }

    /**
     * @return FeedWriter
     */
    public function render()
    {
        foreach ($this->posts as $post) {
            $entry = $this->feedWriter->createEntry();

            $entry->setTitle($post->getTitle());
            $entry->setLink($this->getRenderer()->permaLink($post));
            $entry->setDescription($post->getDescription());
            $entry->setDateModified($post->getModifiedAt());
            $entry->setDateCreated($post->getCreatedAt());

            $this->feedWriter->addEntry($entry);
        }

        $this->feedWriter->export($this->type);

        return $this->feedWriter;
    }
}
