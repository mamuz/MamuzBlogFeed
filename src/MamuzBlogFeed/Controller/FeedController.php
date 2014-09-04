<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlog\Feature\PostQueryInterface;
use MamuzBlogFeed\Feed\Writer\FactoryInterface;
use Zend\EventManager\ListenerAggregateInterface as Listener;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model;

class FeedController extends AbstractActionController
{
    /** @var PostQueryInterface */
    private $postService;

    /** @var Listener */
    private $listener;

    /** @var FactoryInterface */
    private $feedFactory;

    /**
     * @param PostQueryInterface $postService
     * @param Listener           $listener
     * @param FactoryInterface   $feedFactory
     */
    public function __construct(
        PostQueryInterface $postService,
        Listener $listener,
        FactoryInterface $feedFactory
    ) {
        $this->postService = $postService;
        $this->listener = $listener;
        $this->feedFactory = $feedFactory;
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

        $feedOptions = $this->getFeedOptionsBy($tag);
        $feed = $this->feedFactory->create($feedOptions, $posts);

        $feedmodel = new Model\FeedModel;
        $feedmodel->setFeed($feed);

        return $feedmodel;
    }

    /**
     * @param string|null $tag
     * @return array
     */
    private function getFeedOptionsBy($tag = null)
    {
        if (!is_string($tag)) {
            $tag = 'default';
        }

        $config = $this->getServiceLocator()->get('Config')['MamuzBlogFeed'];

        if (isset($config[$tag])) {
            return (array) $config[$tag];
        }

        return array();
    }
}
