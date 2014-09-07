<?php

namespace MamuzBlogFeed\Options;

use MamuzBlogFeed\Filter\FeedOptions;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class ConfigProviderFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     * @return ConfigProviderInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $optionsFilter = new FeedOptions($viewHelperManager->getRenderer());

        $config = $serviceLocator->get('Config')['MamuzBlogFeed'];
        return new ConfigProvider($config, $optionsFilter);
    }
}
