<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlogFeed\View\Helper\FeedFactory;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class FeedControllerFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     * @return \Zend\Mvc\Controller\AbstractController
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var \Zend\ServiceManager\ServiceLocatorInterface $domainManager */
        $domainManager = $serviceLocator->get('MamuzBlog\DomainManager');
        /** @var \MamuzBlog\Feature\PostQueryInterface $postService */
        $postService = $domainManager->get('MamuzBlog\Service\PostQuery');

        $config = $serviceLocator->get('Config')['MamuzBlogFeed'];

        $feedFactory = new FeedFactory($postService, $config);

        return new FeedController($feedFactory);
    }
}
