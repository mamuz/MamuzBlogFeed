<?php

namespace MamuzBlogFeed;

use Zend\EventManager\EventInterface;
use Zend\ModuleManager\Feature;
use Zend\ModuleManager\ModuleManagerInterface;
use Zend\Mvc\MvcEvent;

class Module implements
    Feature\BootstrapListenerInterface,
    Feature\ConfigProviderInterface,
    Feature\InitProviderInterface
{
    public function onBootstrap(EventInterface $e)
    {
        if (!$e instanceof MvcEvent) {
            return;
        }

        $application = $e->getApplication();
        $domainManager = $application->getServiceManager()->get(
            'MamuzBlog\DomainManager'
        );

        /* @var \Zend\EventManager\ListenerAggregateInterface $listenerAggregate */
        $listenerAggregate = $domainManager->get('MamuzBlogFeed\Listener\HeadLinkAggregate');
        $eventManager = $application->getEventManager();
        $eventManager->attachAggregate($listenerAggregate);
    }

    public function init(ModuleManagerInterface $modules)
    {
        $modules->loadModule('MamuzBlog');
    }

    public function getConfig()
    {
        return include __DIR__ . '/../../config/module.config.php';
    }
}
