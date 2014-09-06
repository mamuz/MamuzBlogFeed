<?php

namespace MamuzBlogFeed\Listener;

use MamuzBlogFeed\Filter\Query;
use MamuzBlogFeed\Options\ConfigProvider;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class QueryFilterAggregateFactory implements FactoryInterface
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
        $configProvider = new ConfigProvider($config);

        $queryFilter = new Query($configProvider);

        return new QueryFilterAggregate($queryFilter);
    }
}
