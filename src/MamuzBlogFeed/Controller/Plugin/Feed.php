<?php

namespace MamuzBlogFeed\Controller\Plugin;

use MamuzBlog\Feature\PostQueryInterface;
use MamuzBlogFeed\Feed\Writer\FactoryInterface;
use MamuzBlogFeed\Options\ConfigProviderInterface;
use Zend\Mvc\Controller\AbstractController as MvcController;
use Zend\Mvc\Controller\Plugin\AbstractPlugin;

class Feed extends AbstractPlugin
{
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
     * @return \Zend\Feed\Writer\Feed
     */
    public function create()
    {
        if ($tag = $this->getTagParam()) {
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
