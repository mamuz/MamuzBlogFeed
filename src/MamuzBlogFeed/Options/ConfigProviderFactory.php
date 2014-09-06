<?php

namespace MamuzBlogFeed\Options;

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

        $config = $serviceLocator->get('Config')['MamuzBlogFeed'];
        return new ConfigProvider($config);
    }
}
