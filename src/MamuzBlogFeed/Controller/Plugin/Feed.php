<?php

namespace MamuzBlogFeed\Controller\Plugin;

use MamuzBlog\EventManager\AwareTrait as EventManagerAwareTrait;
use MamuzBlog\Feature\PostQueryInterface;
use MamuzBlogFeed\EventManager\Event;
use MamuzBlogFeed\Feed\Writer\BuilderInterface;
use MamuzBlogFeed\Options\ConfigProviderInterface;
use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\Mvc\Controller\AbstractController as MvcController;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Feed extends AbstractPlugin
{
    use EventManagerAwareTrait;

    /** @var PostQueryInterface */
    private $postService;

    /** @var BuilderInterface */
    private $feedFactory;

    /** @var ConfigProviderInterface */
    private $configProvider;

    /**
     * @param PostQueryInterface      $postService
     * @param BuilderInterface        $feedFactory
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        PostQueryInterface $postService,
        BuilderInterface $feedFactory,
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

    /**
     * @return string|null
     */
    private function getTagParam()
    {
        $controller = $this->getController();
        if ($controller instanceof MvcController) {
            return $controller->params()->fromRoute('tag');
        }

        return null;
    }

    private function createFeedByTag($tag = null)
    {
        if ($tag) {
            $posts = $this->postService->findPublishedPostsByTag($tag);
        } else {
            $posts = $this->postService->findPublishedPosts();
        }

        $feedOptions = $this->configProvider->getFor($tag);

        return $this->feedFactory->create($feedOptions, $posts);
    }
}
