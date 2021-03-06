<?php

namespace MamuzBlogFeed\Controller;

use Zend\EventManager\ListenerAggregateInterface as Listener;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\Mvc\MvcEvent;
use Zend\View\Model;

/**
 * @method \MamuzBlogFeed\Controller\Plugin\Feed feed())
 */
class FeedController extends AbstractActionController
{
    /** @var Listener */
    private $listener;

    /**
     * @param Listener $listener
     */
    public function __construct(Listener $listener)
    {
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
        $feed = $this->feed()->create();

        $feedmodel = new Model\FeedModel;
        $feedmodel->setFeed($feed);

        /** @var \Zend\Http\Response $response */
        $response = $this->getResponse();
        $headers = $response->getHeaders();
        $headers->addHeaderLine('content-type', 'application/xml; charset=' . $feed->getEncoding() . ';');

        return $feedmodel;
    }
}
