<?php

namespace MamuzBlogFeed\Controller;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeedControllerFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     * @return \Zend\Mvc\Controller\AbstractController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var ServiceLocatorInterface $domainManager */
        $domainManager = $serviceLocator->get('MamuzBlog\DomainManager');
        /* @var \Zend\EventManager\ListenerAggregateInterface $listenerAggregate */
        $listenerAggregate = $domainManager->get('MamuzBlogFeed\Listener\QueryFilterAggregate');

        return new FeedController($listenerAggregate);
    }
}
