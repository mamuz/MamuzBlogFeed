<?php

namespace MamuzBlogFeed\Controller\Plugin;

use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorAwareTrait;
use Zend\ServiceManager\ServiceLocatorInterface;

class HeadFeedFactory implements FactoryInterface
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

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $viewHelperManager->get('HeadLink');

        return new HeadFeed($headLink);
    }
}