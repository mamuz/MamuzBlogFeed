<?php

namespace MamuzBlogFeed\Controller\Plugin;

use MamuzBlogFeed\Extractor\FeedEntry;
use MamuzBlogFeed\Feed\Writer\Factory;
use MamuzBlogFeed\Hydrator\Mutator;
use MamuzBlogFeed\Options\ConfigProvider;
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

        $feedFactory = $this->createFeedFactory($serviceLocator);
        $configProvider = $this->createConfigProvider($serviceLocator);

        return new Feed($postService, $feedFactory, $configProvider);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return Factory
     */
    private function createFeedFactory(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $postExtractor = new FeedEntry($viewHelperManager->getRenderer());
        return new Factory($postExtractor, new Mutator);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return ConfigProvider
     */
    private function createConfigProvider(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config')['MamuzBlogFeed'];
        return new ConfigProvider($config);
    }
}
