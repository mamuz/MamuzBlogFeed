<?php

namespace MamuzBlogFeed\View\Helper;

use Zend\Feed\Writer\Feed as FeedWriter;
use Zend\ServiceManager\FactoryInterface;
use Zend\ServiceManager\ServiceLocatorAwareInterface;
use Zend\ServiceManager\ServiceLocatorInterface;

class PostsFeedFactory implements FactoryInterface
{
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

        return new Feed($this->createFeedWriter(), $postService->findPublishedPosts());
    }

    /**
     * @return FeedWriter
     */
    private function createFeedWriter()
    {
        $feedWriter = new FeedWriter;
        $feedWriter->setTitle('Feed Example');
        $feedWriter->setFeedLink('http://ourdomain.com/rss', 'atom');
        $feedWriter->addAuthor(
            array(
                'name'  => 'admin',
                'email' => 'contact@ourdomain.com',
                'uri'   => 'http://www.ourdomain.com',
            )
        );
        $feedWriter->setDescription('Description of this feed');
        $feedWriter->setLink('http://ourdomain.com');
        $feedWriter->setDateModified(time());

        return $feedWriter;
    }
}
