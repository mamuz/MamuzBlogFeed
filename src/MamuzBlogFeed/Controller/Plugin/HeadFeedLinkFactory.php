<?php

namespace MamuzBlogFeed\Controller\Plugin;

use Zend\ServiceManager\ServiceLocatorInterface;

class HeadFeedLinkFactory extends AbstractFactory
{
    public function create(ServiceLocatorInterface $serviceLocator)
    {
        /** @var \Zend\ServiceManager\ServiceLocatorInterface $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        /** @var \Zend\View\Helper\HeadLink $headLink */
        $headLink = $viewHelperManager->get('HeadLink');

        return new HeadFeedLink($headLink);
    }
}
