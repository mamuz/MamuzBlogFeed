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

    /** @var \MamuzBlogFeed\View\Helper\FeedFactoryInterface | \Mockery\MockInterface */
    protected $feedFactory;

    /** @var \MamuzBlogFeed\View\Helper\FeedInterface | \Mockery\MockInterface */
    protected $feed;

    /** @var \Zend\Feed\Writer\Feed | \Mockery\MockInterface */
    protected $feedWriter;

    /** @var \Zend\EventManager\ListenerAggregateInterface | \Mockery\MockInterface */
    protected $listener;

    /** @var \Zend\Mvc\Controller\Plugin\Params | \Mockery\MockInterface */
    protected $params;

    /** @var \Zend\View\Model\ModelInterface | \Mockery\MockInterface */
    protected $viewModel;

    protected function setUp()
    {
        $this->feedWriter = \Mockery::mock('Zend\Feed\Writer\Feed');
        $this->feed = \Mockery::mock('MamuzBlogFeed\View\Helper\FeedInterface');
        $this->feed->shouldReceive('render')->andReturn($this->feedWriter);
        $this->feedFactory = \Mockery::mock('MamuzBlogFeed\View\Helper\FeedFactoryInterface');
        $this->listener = \Mockery::mock('Zend\EventManager\ListenerAggregateInterface')->shouldIgnoreMissing();

        $this->fixture = new FeedController($this->feedFactory, $this->listener);
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
    }

    public function testExtendingZendActionController()
    {
        $this->assertInstanceOf('Zend\Mvc\Controller\AbstractActionController', $this->fixture);
    }

    public function testPostsCanBeAccessed()
    {
        $this->routeMatch->setParam('action', 'posts');
        $this->params->shouldReceive('fromRoute')->with('tag')->andReturn(null);

        $this->feedFactory
            ->shouldReceive('create')
            ->with(null)
            ->andReturn($this->feed);

        $result = $this->fixture->dispatch($this->request);
        $response = $this->fixture->getResponse();

        $this->assertSame($this->feedWriter, $result->getFeed());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
