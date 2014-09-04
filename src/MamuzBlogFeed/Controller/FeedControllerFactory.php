<?php

namespace MamuzBlogFeed\Controller;

use MamuzBlogFeed\Extractor\FeedEntry;
use MamuzBlogFeed\Hydrator\Mutator;
use MamuzBlogFeed\Feed\Writer\Factory;
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

        /** @var ServiceLocatorInterface $domainManager */
        $domainManager = $serviceLocator->get('MamuzBlog\DomainManager');
        /** @var \MamuzBlog\Feature\PostQueryInterface $postService */
        $postService = $domainManager->get('MamuzBlog\Service\PostQuery');

        /* @var \Zend\EventManager\ListenerAggregateInterface $listenerAggregate */
        $listenerAggregate = $domainManager->get('MamuzBlogFeed\Listener\Aggregate');

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $postExtractor = new FeedEntry($viewHelperManager->getRenderer());
        $feedFactory = new Factory($postExtractor, new Mutator);

        return new FeedController($postService, $listenerAggregate, $feedFactory);
    }
}
