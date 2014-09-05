<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlog\Feature\PostQueryInterface;
use MamuzBlogFeed\Feed\Writer\FactoryInterface;
use MamuzBlogFeed\Options\ConfigProviderInterface;
use Zend\EventManager\ListenerAggregateInterface as Listener;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model;

/**
 * @method \MamuzBlogFeed\Controller\Plugin\HeadFeed headFeed())
 */
class FeedController extends AbstractActionController
{
    /** @var PostQueryInterface */
    private $postService;

    /** @var Listener */
    private $listener;

    /** @var FactoryInterface */
    private $feedFactory;

    /** @var ConfigProviderInterface */
    private $configProvider;

    /**
     * @param PostQueryInterface      $postService
     * @param Listener                $listener
     * @param FactoryInterface        $feedFactory
     * @param ConfigProviderInterface $configProvider
     */
    public function __construct(
        PostQueryInterface $postService,
        Listener $listener,
        FactoryInterface $feedFactory,
        ConfigProviderInterface $configProvider
    ) {
        $this->postService = $postService;
        $this->listener = $listener;
        $this->feedFactory = $feedFactory;
        $this->configProvider = $configProvider;
    }

    public function onDispatch(MvcEvent $event)
    {
        $this->getEventManager()->attachAggregate($this->listener);
        return parent::onDispatch($event);
    }

    /**
     * @return Model\ModelInterface
     */
    public function postsAction()
    {
        if ($tag = $this->params()->fromRoute('tag')) {
            $posts = $this->postService->findPublishedPostsByTag($tag);
        } else {
            $posts = $this->postService->findPublishedPosts();
        }

        $feedOptions = $this->configProvider->getFor($tag);
        /** @var \IteratorAggregate $posts */
        $feed = $this->feedFactory->create($feedOptions, $posts);

        $this->headFeed()->add($feed);

        $feedmodel = new Model\FeedModel;
        $feedmodel->setFeed($feed);

        return $feedmodel;
    }
}
