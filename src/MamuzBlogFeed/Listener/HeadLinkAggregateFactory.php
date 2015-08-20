<?php

namespace MamuzBlogFeed\Listener;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class HeadLinkAggregateFactory implements FactoryInterface
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

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $viewHelperManager->get('HeadLink');
        /** @var ServiceLocatorInterface $domainManager */
        $domainManager = $serviceLocator->get('MamuzBlog\DomainManager');
        /** @var \MamuzBlogFeed\Feed\Writer\BuilderInterface $builder */
        $builder = $domainManager->get('MamuzBlogFeed\Feed\WriterFactory');
        /** @var \MamuzBlogFeed\Options\ConfigProviderInterface $configProvider */
        $configProvider = $domainManager->get('MamuzBlogFeed\Options\ConfigProvider');

        return new HeadLinkAggregate($headLink, $builder, $configProvider);
    }
}
