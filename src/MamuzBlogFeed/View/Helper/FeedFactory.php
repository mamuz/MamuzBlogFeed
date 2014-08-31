<?php

namespace MamuzBlogFeed\View\Helper;

use MamuzBlog\Feature\PostQueryInterface;
use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\View\Renderer\RendererInterface;

class FeedFactory
{
    /** @var RendererInterface */
    private $renderer;

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
     * @param RendererInterface  $renderer
     * @param array              $config
     */
    public function __construct(PostQueryInterface $postService, RendererInterface $renderer, array $config)
    {
        $this->postService = $postService;
        $this->renderer = $renderer;
        $this->config = $config;
    }

    /**
     * @param  string|null $tag
     * @return Feed
     */
    public function create($tag = null)
    {
        if ($tag) {
            $posts = $this->postService->findPublishedPostsByTag($tag);
        } else {
            $tag = 'default';
            $posts = $this->postService->findPublishedPosts();
        }

        $config = $this->getConfigBy($tag);
        $this->createFeedWriter($config);
        $this->createEntryPrototype();

        $feed = new Feed(
            $this->feedWriter,
            $this->entryPrototype,
            $posts,
            $this->renderer
        );

        return $feed;
    }

    /**
     * @param string $tag
     * @return array
     */
    private function getConfigBy($tag)
    {
        if (isset($this->config[$tag])) {
            return (array) $this->config[$tag];
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
        $this->feedWriter->setType('rss');
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