<?php

namespace MamuzBlogFeed;

use Zend\EventManager\EventInterface;
use Zend\EventManager\EventManagerInterface;
use Zend\ModuleManager\Feature;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\ApplicationInterface;
use Zend\Mvc\MvcEvent;
use Zend\ServiceManager\ServiceLocatorInterface;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\BootstrapListenerInterface,
    Feature\ConfigProviderInterface,
    Feature\InitProviderInterface
{
    /** @var ApplicationInterface */
    private $application;

    /** @var ServiceLocatorInterface */
    private $domainManager;

    /** @var EventManagerInterface */
    private $eventManager;

    public function onBootstrap(EventInterface $e)
    {
        /** @var MvcEvent $e */
        $this->application = $e->getApplication();
        $this->eventManager = $this->application->getEventManager();
        $this->domainManager = $this->application->getServiceManager()->get(
            'MamuzBlog\DomainManager'
        );

        /* @var \Zend\EventManager\ListenerAggregateInterface $listenerAggregate */
        $listenerAggregate = $this->domainManager->get('MamuzBlogFeed\Listener\HeadLinkAggregate');
        $this->eventManager->attachAggregate($listenerAggregate);
    }

    public function init(ModuleManagerInterface $modules)
    {
        $modules->loadModule('MamuzBlog');
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/../../autoload_classmap.php',
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__,
                ),
            ),
        );
    }
}
