<?php

namespace MamuzBlogFeed\View\Helper;

use MamuzBlog\Feature\PostQueryInterface;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\ServiceManager\ServiceLocatorAwareTrait;

class FeedFactory
{
    use ServiceLocatorAwareTrait;

    const STANDARD_FEED_TYPE = 'rss';

    /** @var FeedWriter */
    private $feedWriter;

    /** @var Entry */
    private $entryPrototype;

    /** @var array */
    private $config = array();

    /** @var PostQueryInterface */
    private $postService;

    /**
     * @param PostQueryInterface $postService
     * @param array              $config
     */
    public function __construct(PostQueryInterface $postService, array $config)
    {
        $this->postService = $postService;
        $this->config = $config;
    }

    /**
     * @param  string|null $name
     * @return Feed
     */
    public function create($name = null)
    {
        if ($name) {
            $posts = $this->postService->findPublishedPostsByTag($name);
        } else {
            $name = 'postsFeed';
            $posts = $this->postService->findPublishedPosts();
        }

        $config = $this->getConfigBy($name);
        $this->createFeedWriter($config);
        $this->createEntryPrototype();

        return new Feed(
            $this->feedWriter,
            $this->entryPrototype,
            $posts
        );
    }

    /**
     * @param string $name
     * @return array
     */
    private function getConfigBy($name)
    {
        if (isset($this->config[$name])) {
            return (array) $this->config[$name];
        }

        return array();
    }

    /**
     * @param array $config
     * @return void
     */
    private function createFeedWriter(array $config)
    {
        $this->feedWriter = new FeedWriter;
        $this->feedWriter->setType(self::STANDARD_FEED_TYPE);
        $this->feedWriter->setDateModified(time());

        foreach ($config as $key => $value) {
            $setMethod = 'set' . ucfirst($key);
            $addMethod = 'add' . ucfirst($key);
            if (is_callable(array($this->feedWriter, $setMethod))) {
                $this->feedWriter->$setMethod($value);
            } elseif (is_callable(array($this->feedWriter, $addMethod))) {
                $this->feedWriter->$addMethod($value);
            }
        }
    }

    /**
     * @return void
     */
    private function createEntryPrototype()
    {
        $this->entryPrototype = $this->feedWriter->createEntry();
        $this->entryPrototype->setCopyright($this->feedWriter->getCopyright());
        $this->entryPrototype->addAuthors($this->feedWriter->getAuthors());
    }
}
