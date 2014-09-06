<?php

namespace MamuzBlogFeed\Controller\Plugin;

use MamuzBlog\EventManager\AwareTrait as EventManagerAwareTrait;
use MamuzBlog\Feature\PostQueryInterface;
use MamuzBlogFeed\EventManager\Event;
use MamuzBlogFeed\Feed\Writer\FactoryInterface;
use MamuzBlogFeed\Options\ConfigProviderInterface;
use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\Mvc\Controller\AbstractController as MvcController;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Feed extends AbstractPlugin
{
    use EventManagerAwareTrait;

    /** @var PostQueryInterface */
    private $postService;

    /** @var FactoryInterface */
    private $feedFactory;

    /** @var ConfigProviderInterface */
    private $configProvider;

    /**
     * @param PostQueryInterface      $postService
     * @param FactoryInterface        $feedFactory
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        PostQueryInterface $postService,
        FactoryInterface $feedFactory,
        ConfigProviderInterface $configProvider
    ) {
        $this->postService = $postService;
        $this->feedFactory = $feedFactory;
        $this->configProvider = $configProvider;
    }

    /**
     * @return FeedWriter
     */
    public function create()
    {
        $tag = $this->getTagParam();

        $results = $this->trigger(Event::PRE_FEED_CREATE, array('tag' => $tag));
        if ($results->stopped() && ($feed = $results->last()) instanceof FeedWriter) {
            return $feed;
        }

        $feed = $this->createFeedByTag($tag);

        $this->trigger(Event::POST_FEED_CREATE, array('feed' => $feed));

        return $feed;
    }

    private function createFeedByTag($tag = null)
    {
        if ($tag) {
            $posts = $this->postService->findPublishedPostsByTag($tag);
        } else {
            $posts = $this->postService->findPublishedPosts();
        }

        $feedOptions = $this->configProvider->getFor($tag);

        /** @var \IteratorAggregate $posts */
        return $this->feedFactory->create($feedOptions, $posts);
    }

    /**
     * @return string|null
     */
    private function getTagParam()
    {
        if ($mvcController = $this->getMvcController()) {
            return $mvcController->params()->fromRoute('tag');
        }

        return null;
    }

    /**
     * @return MvcController|null
     */
    private function getMvcController()
    {
        $controller = $this->getController();
        if ($controller instanceof MvcController) {
            return $controller;
        }

        return null;
    }
}
