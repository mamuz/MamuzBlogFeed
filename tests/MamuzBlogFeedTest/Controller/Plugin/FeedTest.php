<?php

namespace MamuzBlogFeedTest\Controller\Plugin;

use MamuzBlogFeed\Controller\Plugin\Feed;

class FeedTest extends \PHPUnit_Framework_TestCase
{
    /** @var Feed */
    protected $fixture;

    /** @var \MamuzBlogFeed\Feed\Writer\FactoryInterface | \Mockery\MockInterface */
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

    protected function setUp()
    {
        $this->params = \Mockery::mock('Zend\Mvc\Controller\Plugin\Params');
        $this->mvcController = \Mockery::mock('Zend\Mvc\Controller\AbstractController');
        $this->posts = new \ArrayObject;
        $this->postService = \Mockery::mock('MamuzBlog\Feature\PostQueryInterface');
        $this->serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $this->feedWriter = \Mockery::mock('Zend\Feed\Writer\Feed');
        $this->feedFactory = \Mockery::mock('MamuzBlogFeed\Feed\Writer\FactoryInterface');
        $this->configProvider = \Mockery::mock('MamuzBlogFeed\Options\ConfigProviderInterface');

        $this->fixture = new Feed($this->postService, $this->feedFactory, $this->configProvider);
    }

    public function testImplementingFactoryInterface()
    {
        $this->assertInstanceOf('Zend\Mvc\Controller\Plugin\AbstractPlugin', $this->fixture);
    }

    public function testCreationWithoutController()
    {
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
}
