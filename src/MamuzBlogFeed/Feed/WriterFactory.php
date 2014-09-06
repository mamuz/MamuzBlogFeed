<?php

namespace MamuzBlogFeed\Feed;

use MamuzBlogFeed\Extractor\FeedEntry;
use MamuzBlogFeed\Feed\Writer;
use MamuzBlogFeed\Hydrator\Mutator;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class WriterFactory implements FactoryInterface
{
    /**
     * {@inheritdoc}
     * @return Writer\FactoryInterface
     */
    public function createService(ServiceLocatorInterface $serviceLocator)
    {
        if ($serviceLocator instanceof ServiceLocatorAwareInterface) {
            $serviceLocator = $serviceLocator->getServiceLocator();
        }

        /** @var \Zend\View\HelperPluginManager $viewHelperManager */
        $viewHelperManager = $serviceLocator->get('ViewHelperManager');
        $postExtractor = new FeedEntry($viewHelperManager->getRenderer());

        return new Writer\Factory($postExtractor, new Mutator);
    }
}
