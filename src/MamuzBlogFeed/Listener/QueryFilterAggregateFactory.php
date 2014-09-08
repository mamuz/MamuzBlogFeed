<?php

namespace MamuzBlogFeed\Listener;

use MamuzBlogFeed\Filter\Query;
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

        /** @var ServiceLocatorInterface $domainManager */
        $domainManager = $serviceLocator->get('MamuzBlog\DomainManager');
        /** @var \MamuzBlogFeed\Options\ConfigProviderInterface $configProvider */
        $configProvider = $domainManager->get('MamuzBlogFeed\Options\ConfigProvider');

        $queryFilter = new Query($configProvider);

        return new QueryFilterAggregate($queryFilter);
    }
}
