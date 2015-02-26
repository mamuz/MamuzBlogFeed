<?php

namespace MamuzBlogFeedTest\Controller\Plugin;

use MamuzBlogFeed\Controller\Plugin\Feed;
use MamuzBlogFeed\EventManager\Event;

class FeedTest extends \PHPUnit_Framework_TestCase
{
    /** @var Feed */
    protected $fixture;

    /** @var \MamuzBlogFeed\Feed\Writer\BuilderInterface | \Mockery\MockInterface */
    protected $feedFactory;

    /** @var \Zend\Feed\Writer\Feed | \Mockery\MockInterface */
    protected $feedWriter;

    /** @var \Zend\ServiceManager\ServiceLocatorInterface | \Mockery\MockInterface */
    protected $serviceLocator;

    /** @var \Zend\Mvc\Controller\Plugin\Params | \Mockery\MockInterface */
    protected $params;

    /** @var \MamuzBlog\Feature\PostQueryInterface | \Mockery\MockInterface */
    protected $postService;

    /** @var \ArrayObject */
    protected $posts;

    /** @var \MamuzBlogFeed\Options\ConfigProviderInterface |\Mockery\MockInterface */
    protected $configProvider;

    /** @var \Zend\Mvc\Controller\AbstractController | \Mockery\MockInterface */
    protected $mvcController;

    /** @var \Zend\EventManager\EventManagerInterface | \Mockery\MockInterface */
    protected $eventManager;

    /** @var \Zend\EventManager\ResponseCollection | \Mockery\MockInterface */
    protected $reponseCollection;

    protected function setUp()
    {
        $this->params = \Mockery::mock('Zend\Mvc\Controller\Plugin\Params');
        $this->mvcController = \Mockery::mock('Zend\Mvc\Controller\AbstractController');
        $this->posts = new \ArrayObject;
        $this->postService = \Mockery::mock('MamuzBlog\Feature\PostQueryInterface');
        $this->serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $this->feedWriter = \Mockery::mock('Zend\Feed\Writer\Feed');
        $this->feedFactory = \Mockery::mock('MamuzBlogFeed\Feed\Writer\BuilderInterface');
        $this->configProvider = \Mockery::mock('MamuzBlogFeed\Options\ConfigProviderInterface');

        $this->fixture = new Feed($this->postService, $this->feedFactory, $this->configProvider);

        $this->eventManager = \Mockery::mock('Zend\EventManager\EventManagerInterface');
        $this->fixture->setEventManager($this->eventManager);

        $this->reponseCollection = \Mockery::mock('Zend\EventManager\ResponseCollection')->shouldIgnoreMissing();
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\Mvc\Controller\Plugin\AbstractPlugin', $this->fixture);
    }

    protected function prepareEventManager($tag = null, $stopped = false, $feedIsNull = false)
    {
        $this->reponseCollection->shouldReceive('stopped')->once()->andReturn($stopped);

        if ($stopped) {
            $this->reponseCollection->shouldReceive('last')->andReturn($feedIsNull ? null : $this->feedWriter);
        }

        $this->eventManager->shouldReceive('trigger')->once()->with(
            Event::PRE_FEED_CREATE,
            $this->fixture,
            array('tag' => $tag)
        )->andReturn($this->reponseCollection);
    }

    public function testCreationWithoutController()
    {
        $this->prepareEventManager();
        $this->eventManager->shouldReceive('trigger')->with(
            Event::POST_FEED_CREATE,
            $this->fixture,
            array('feed' => $this->feedWriter)
        );
        $this->postService->shouldReceive('findPublishedPosts')->andReturn($this->posts);
        $this->configProvider->shouldReceive('getFor')->with(null)->andReturn(array(1));

        $this->feedFactory
            ->shouldReceive('create')
            ->with(array(1), $this->posts)
            ->andReturn($this->feedWriter);

        $this->assertSame($this->feedWriter, $this->fixture->create());
    }

    public function testCreationWithControllerAndWithoutTag()
    {
        $this->prepareEventManager();
        $this->eventManager->shouldReceive('trigger')->with(
            Event::POST_FEED_CREATE,
            $this->fixture,
            array('feed' => $this->feedWriter)
        );
        $this->fixture->setController($this->mvcController);
        $this->mvcController->shouldReceive('params')->andReturn($this->params);
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturnNull();
        $this->postService->shouldReceive('findPublishedPosts')->andReturn($this->posts);
        $this->configProvider->shouldReceive('getFor')->with(null)->andReturn(array(1));

        $this->feedFactory
            ->shouldReceive('create')
            ->with(array(1), $this->posts)
            ->andReturn($this->feedWriter);

        $this->assertSame($this->feedWriter, $this->fixture->create());
    }

    public function testCreationWithControllerAndWithTag()
    {
        $this->prepareEventManager('foo');
        $this->eventManager->shouldReceive('trigger')->with(
            Event::POST_FEED_CREATE,
            $this->fixture,
            array('feed' => $this->feedWriter)
        );
        $this->fixture->setController($this->mvcController);
        $this->mvcController->shouldReceive('params')->andReturn($this->params);
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturn('foo');
        $this->postService->shouldReceive('findPublishedPostsByTag')->with('foo')->andReturn($this->posts);
        $this->configProvider->shouldReceive('getFor')->with('foo')->andReturn(array(1));

        $this->feedFactory
            ->shouldReceive('create')
            ->with(array(1), $this->posts)
            ->andReturn($this->feedWriter);

        $this->assertSame($this->feedWriter, $this->fixture->create());
    }

    public function testCreationWithEventNullResponse()
    {
        $this->prepareEventManager('foo', true, true);
        $this->eventManager->shouldReceive('trigger')->with(
            Event::POST_FEED_CREATE,
            $this->fixture,
            array('feed' => $this->feedWriter)
        );
        $this->fixture->setController($this->mvcController);
        $this->mvcController->shouldReceive('params')->andReturn($this->params);
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturn('foo');
        $this->postService->shouldReceive('findPublishedPostsByTag')->with('foo')->andReturn($this->posts);
        $this->configProvider->shouldReceive('getFor')->with('foo')->andReturn(array(1));

        $this->feedFactory
            ->shouldReceive('create')
            ->with(array(1), $this->posts)
            ->andReturn($this->feedWriter);

        $this->assertSame($this->feedWriter, $this->fixture->create());
    }

    public function testCreationWithEventResponse()
    {
        $this->prepareEventManager('foo', true);
        $this->eventManager->shouldReceive('trigger')->with(
            Event::POST_FEED_CREATE,
            $this->fixture,
            array('feed' => $this->feedWriter)
        );
        $this->fixture->setController($this->mvcController);
        $this->mvcController->shouldReceive('params')->andReturn($this->params);
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturn('foo');

        $this->assertSame($this->feedWriter, $this->fixture->create());
    }
}
