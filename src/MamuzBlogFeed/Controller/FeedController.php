<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlogFeed\View\Helper\FeedFactoryInterface;
use Zend\EventManager\ListenerAggregateInterface as Listener;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model;

class FeedController extends AbstractActionController
{
    /** @var FeedFactoryInterface */
    private $feedFactory;

    /** @var Listener */
    private $listener;

    /**
     * @param FeedFactoryInterface $feedFactory
     * @param Listener             $listener
     */
    public function __construct(FeedFactoryInterface $feedFactory, Listener $listener)
    {
        $this->feedFactory = $feedFactory;
        $this->listener = $listener;
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
        $tag = $this->params()->fromRoute('tag');
        $feedWriter = $this->feedFactory->create($tag);

        $feedmodel = new Model\FeedModel;
        $feedmodel->setFeed($feedWriter->render());

        return $feedmodel;
    }
}
