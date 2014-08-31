<?php

namespace MamuzBlogFeed\Listener;

use MamuzBlogFeed\Filter\Query;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class AggregateFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     * @return \Zend\EventManager\ListenerAggregateInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        $config = $serviceLocator->get('Config')['MamuzBlogFeed'];
        $queryFilter = new Query($config);

        return new Aggregate($queryFilter);
    }
}
