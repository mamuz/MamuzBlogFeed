<?php

namespace MamuzBlogFeed\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeedFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     * @return \Zend\Mvc\Controller\Plugin\AbstractPlugin
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var ServiceLocatorInterface $domainManager */
        $domainManager = $serviceLocator->get('MamuzBlog\DomainManager');
        /** @var \MamuzBlog\Feature\PostQueryInterface $postService */
        $postService = $domainManager->get('MamuzBlog\Service\PostQuery');
        /** @var \MamuzBlogFeed\Feed\Writer\FactoryInterface $feedFactory */
        $feedFactory = $domainManager->get('MamuzBlogFeed\Feed\WriterFactory');
        /** @var \MamuzBlogFeed\Options\ConfigProviderInterface $configProvider */
        $configProvider = $domainManager->get('MamuzBlogFeed\Options\ConfigProvider');

        return new Feed($postService, $feedFactory, $configProvider);
    }
}
