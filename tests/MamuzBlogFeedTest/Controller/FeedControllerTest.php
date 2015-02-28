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

    /** @var \Zend\Feed\Writer\Feed | \Mockery\MockInterface */
    protected $feedWriter;

    /** @var \Zend\EventManager\ListenerAggregateInterface | \Mockery\MockInterface */
    protected $listener;

    /** @var \MamuzBlogFeed\Controller\Plugin\Feed |\Mockery\MockInterface */
    protected $headFeedLink;

    /** @var \MamuzBlogFeed\Controller\Plugin\Feed |\Mockery\MockInterface */
    protected $feed;

    protected function setUp()
    {
        $this->feedWriter = \Mockery::mock('Zend\Feed\Writer\Feed');
        $this->feedWriter->shouldReceive('getEncoding')->andReturn('UTF-8');
        $this->listener = \Mockery::mock('Zend\EventManager\ListenerAggregateInterface')->shouldIgnoreMissing();

        $this->fixture = new FeedController($this->listener);

        $this->request = new Request();
        $this->routeMatch = new RouteMatch(array('controller' => 'posts'));
        $this->event = new MvcEvent();
        $router = HttpRouter::factory();

        $this->feed = \Mockery::mock('MamuzBlogFeed\Controller\Plugin\Feed');
        $this->feed->shouldReceive('create')->andReturn($this->feedWriter);
        $this->headFeedLink = \Mockery::mock('MamuzBlogFeed\Controller\Plugin\Feed');
        $this->headFeedLink->shouldReceive('add')->with($this->feedWriter);

        $pluginManager = \Mockery::mock('Zend\Mvc\Controller\PluginManager')->shouldIgnoreMissing();
        $pluginManager->shouldReceive('get')->with('feed', null)->andReturn($this->feed);
        $pluginManager->shouldReceive('get')->with('headFeedLink', null)->andReturn($this->headFeedLink);

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
        $result = $this->fixture->dispatch($this->request);
        $response = $this->fixture->getResponse();

        $this->assertSame($this->feedWriter, $result->getFeed());
        $this->assertEquals(200, $response->getStatusCode());
    }
}
