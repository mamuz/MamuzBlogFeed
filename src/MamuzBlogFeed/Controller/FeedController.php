<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlogFeed\View\Helper\FeedFactory;
use Zend\EventManager\ListenerAggregateInterface;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model;

class FeedController extends AbstractActionController
{
    /** @var FeedFactory */
    private $feedFactory;

    /** @var ListenerAggregateInterface */
    private $listenerAggregate;

    public function __construct(FeedFactory $feedFactory, ListenerAggregateInterface $listenerAggregate)
    {
        $this->feedFactory = $feedFactory;
        $this->listenerAggregate = $listenerAggregate;
    }

    public function onDispatch(MvcEvent $event)
    {
        $this->getEventManager()->attachAggregate($this->listenerAggregate);
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
