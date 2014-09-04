<?php

namespace MamuzBlogFeedTest\Controller;

use MamuzBlogFeed\Controller\FeedController;
use Zend\Http\PhpEnvironment\Request;
use Zend\Http\PhpEnvironment\Response;
use Zend\Mvc\MvcEvent;
use Zend\Mvc\Router\Http\TreeRouteStack as HttpRouter;
use Zend\Mvc\Router\RouteMatch;

class FeedControllerTest extends \PHPUnit_Framework_TestCase
{
    /** @var \Zend\Mvc\Controller\AbstractActionController */
    protected $fixture;

    /** @var Request */
    protected $request;

    /** @var Response */
    protected $response;

    /** @var RouteMatch */
    protected $routeMatch;

    /** @var MvcEvent */
    protected $event;

    /** @var \MamuzBlogFeed\Feed\Writer\FactoryInterface | \Mockery\MockInterface */
    protected $feedFactory;

    /** @var \Zend\Feed\Writer\Feed | \Mockery\MockInterface */
    protected $feedWriter;

    /** @var \Zend\EventManager\ListenerAggregateInterface | \Mockery\MockInterface */
    protected $listener;

    /** @var \Zend\ServiceManager\ServiceLocatorInterface | \Mockery\MockInterface */
    protected $serviceLocator;

    /** @var \Zend\Mvc\Controller\Plugin\Params | \Mockery\MockInterface */
    protected $params;

    /** @var \Zend\View\Model\ModelInterface | \Mockery\MockInterface */
    protected $viewModel;

    /** @var \MamuzBlog\Feature\PostQueryInterface | \Mockery\MockInterface */
    protected $postService;

    /** @var \ArrayObject */
    protected $posts;

    /** @var array */
    protected $config = array('MamuzBlogFeed' => array('default' => array(1, 2), 'foo' => array('bar')));

    protected function setUp()
    {
        $this->posts = new \ArrayObject;
        $this->postService = \Mockery::mock('MamuzBlog\Feature\PostQueryInterface');
        $this->serviceLocator = \Mockery::mock('Zend\ServiceManager\ServiceLocatorInterface');
        $this->serviceLocator->shouldReceive('get')->with('Config')->andReturn($this->config);
        $this->feedWriter = \Mockery::mock('Zend\Feed\Writer\Feed');
        $this->feedFactory = \Mockery::mock('MamuzBlogFeed\Feed\Writer\FactoryInterface');
        $this->listener = \Mockery::mock('Zend\EventManager\ListenerAggregateInterface')->shouldIgnoreMissing();

        $this->fixture = new FeedController($this->postService, $this->listener, $this->feedFactory);
        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'posts'));
        $this->event = new MvcEvent();
        $router = HttpRouter::factory();

        $this->params = \Mockery::mock('Zend\Mvc\Controller\Plugin\Params');
        $this->params->shouldReceive('__invoke')->andReturn($this->params);
        $pluginManager = \Mockery::mock('Zend\Mvc\Controller\PluginManager')->shouldIgnoreMissing();
        $pluginManager->shouldReceive('get')->with('params', null)->andReturn($this->params);

        $this->fixture->setPluginManager($pluginManager);
        $this->event->setRouter($router);
        $this->event->setRouteMatch($this->routeMatch);
        $this->fixture->setEvent($this->event);
        $this->fixture->setServiceLocator($this->serviceLocator);
    }

    public function testExtendingZendActionController()
    {
        $this->assertInstanceOf('Zend\Mvc\Controller\AbstractActionController', $this->fixture);
    }

    public function testPostsCanBeAccessedWithNullTag()
    {
        $this->routeMatch->setParam('action', 'posts');
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturn(null);

        $this->postService->shouldReceive('findPublishedPosts')->andReturn($this->posts);

        $this->feedFactory
            ->shouldReceive('create')
            ->with($this->config['MamuzBlogFeed']['default'], $this->posts)
            ->andReturn($this->feedWriter);

        $result = $this->fixture->dispatch($this->request);
        $response = $this->fixture->getResponse();

        $this->assertSame($this->feedWriter, $result->getFeed());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostsCanBeAccessedWithTag()
    {
        $this->routeMatch->setParam('action', 'posts');
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturn('foo');

        $this->postService->shouldReceive('findPublishedPostsByTag')->with('foo')->andReturn($this->posts);

        $this->feedFactory
            ->shouldReceive('create')
            ->with($this->config['MamuzBlogFeed']['foo'], $this->posts)
            ->andReturn($this->feedWriter);

        $result = $this->fixture->dispatch($this->request);
        $response = $this->fixture->getResponse();

        $this->assertSame($this->feedWriter, $result->getFeed());
        $this->assertEquals(200, $response->getStatusCode());
    }

    public function testPostsCanBeAccessedWithTagWithoutFeedOptions()
    {
        $this->routeMatch->setParam('action', 'posts');
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturn('bar');

        $this->postService->shouldReceive('findPublishedPostsByTag')->with('bar')->andReturn($this->posts);

        $this->feedFactory
            ->shouldReceive('create')
            ->with(array(), $this->posts)
            ->andReturn($this->feedWriter);

        $result = $this->fixture->dispatch($this->request);
        $response = $this->fixture->getResponse();

        $this->assertSame($this->feedWriter, $result->getFeed());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
