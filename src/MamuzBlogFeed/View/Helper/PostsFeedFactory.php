<?php

namespace MamuzBlogFeed\View\Helper;

use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostsFeedFactory implements FactoryInterface
{
    const STANDARD_FEED_TYPE = 'rss';

    /** @var FeedWriter */
    private $feedWriter;

    /** @var Entry */
    private $entryPrototype;

    /** @var array */
    private $config = array();

    /**
     * {@inheritdoc}
     * @return \Zend\View\Helper\HelperInterface
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

        $this->setConfigBy($serviceLocator);
        $this->createFeedWriter();
        $this->createEntryPrototype();

        return new Feed(
            $this->feedWriter,
            $this->entryPrototype,
            $postService->findPublishedPosts()
        );
    }

    /**
     * @param ServiceLocatorInterface $serviceLocator
     * @return void
     */
    private function setConfigBy(ServiceLocatorInterface $serviceLocator)
    {
        $config = $serviceLocator->get('Config');
        if (isset($config['MamuzBlogFeed']['postsFeed'])) {
            $this->config = (array) $config['MamuzBlogFeed']['postsFeed'];
        }
    }

    /**
     * @return void
     */
    private function createFeedWriter()
    {
        $this->feedWriter = new FeedWriter;
        $this->feedWriter->setType(self::STANDARD_FEED_TYPE);
        $this->feedWriter->setDateModified(time());

        foreach ($this->config as $key => $value) {
            $setMethod = 'set' . ucfirst($key);
            $addMethod = 'add' . ucfirst($key);
            if (is_callable(array($this->feedWriter, $setMethod))) {
                $this->feedWriter->$setMethod($value);
            } elseif (is_callable(array($this->feedWriter, $addMethod))) {
                $this->feedWriter->$addMethod($value);
            }
        }
    }

    /**
     * @return void
     */
    private function createEntryPrototype()
    {
        $this->entryPrototype = $this->feedWriter->createEntry();
        $this->entryPrototype->setCopyright($this->feedWriter->getCopyright());
        $this->entryPrototype->addAuthors($this->feedWriter->getAuthors());
    }
}
