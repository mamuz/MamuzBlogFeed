<?php

namespace MamuzBlogFeed\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

abstract class AbstractFactory implements FactoryInterface
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

        return $this->create($serviceLocator);
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return \Zend\Mvc\Controller\Plugin\AbstractPlugin
     */
    abstract protected function create(ServiceLocatorInterface $serviceLocator);
}
