<?php

namespace MamuzBlogFeed\View\Helper;

use Zend\Feed\Writer\Entry;
use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostsFeedFactory implements FactoryInterface
{
    /** @var FeedWriter */
    private $feedWriter;
    /** @var Entry */
    private $entryPrototype;

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

        $this->createFeedWriter();
        $this->createEntryPrototype();

        return new Feed(
            $this->feedWriter,
            $this->entryPrototype,
            $postService->findPublishedPosts()
        );
    }

    /**
     * @return void
     */
    private function createFeedWriter()
    {
        $this->feedWriter = new FeedWriter;
        $this->feedWriter->setType('rss'); // required
        $this->feedWriter->setTitle('Feed Example');
        $this->feedWriter->setFeedLink('http://ourdomain.com/rss', 'atom');
        $this->feedWriter->addAuthors(
            array(
                'name'  => 'admin',
                'email' => 'contact@ourdomain.com',
                'uri'   => 'http://www.ourdomain.com',
            )
        );
        $this->feedWriter->setDescription('Description of this feed');
        $this->feedWriter->setLink('http://ourdomain.com');
        $this->feedWriter->setDateModified(time());
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
