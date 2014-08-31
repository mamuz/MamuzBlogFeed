<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlogFeed\View\Helper\FeedFactory;
use Zend\EventManager\ListenerAggregateInterface as Listener;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model;

class FeedController extends AbstractActionController
{
    /** @var FeedFactory */
    private $feedFactory;

    /** @var Listener */
    private $listener;

    /**
     * @param FeedFactory $feedFactory
     * @param Listener    $listener
     */
    public function __construct(FeedFactory $feedFactory, Listener $listener)
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
