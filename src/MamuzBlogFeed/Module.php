<?php

namespace MamuzBlogFeed;

use Zend\ModuleManager\Feature;
use Zend\ModuleManager\Listener\ServiceListenerInterface;
use Zend\ModuleManager\ModuleManager;
use Zend\ModuleManager\ModuleManagerInterface;

class Module implements
    Feature\AutoloaderProviderInterface,
    Feature\ConfigProviderInterface,
    Feature\InitProviderInterface
{
    public function init(ModuleManagerInterface $modules)
    {
        $modules->loadModule('DoctrineModule');
        $modules->loadModule('DoctrineORMModule');
        $modules->loadModule('TwbBundle');

        if ($modules instanceof ModuleManager) {
            $this->addDomainManager($modules);
        }
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

    private function addDomainManager(ModuleManager $modules)
    {
        /** @var \Zend\ServiceManager\ServiceLocatorInterface $sm */
        $sm = $modules->getEvent()->getParam('ServiceManager');
        /** @var ServiceListenerInterface $serviceListener */
        $serviceListener = $sm->get('ServiceListener');

        $serviceListener->addServiceManager(
            'MamuzBlogFeed\DomainManager',
            'blog_feed_domain',
            'MamuzBlogFeed\DomainManager\ProviderInterface',
            'getBlogFeedDomainConfig'
        );
    }
}
