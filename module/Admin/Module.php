<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonModule for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Admin;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface;
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Admin\Model\DbTable\UserTable;
use Zend\Authentication\Adapter\DbTable as AuthAdapterDbTable, Zend\Authentication\AuthenticationService;
use Commons\StaticOptions;

class Module extends \Commons\Module implements AutoloaderProviderInterface
{

    protected $_namespace = __NAMESPACE__;

    protected $_dir = __DIR__;

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\ClassMapAutoloader' => array(
                __DIR__ . '/autoload_classmap.php'
            ),
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    // if we're in a namespace deeper than one level we need to fix the \ in the path
                    __NAMESPACE__ => __DIR__ . '/src/' . str_replace('\\', '/', __NAMESPACE__)
                )
            )
        );
    }

    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function onBootstrap(MvcEvent $e)
    {
        // You may not need to do this if you're doing it elsewhere in your
        // application
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        $eventManager->attach('dispatch', array(
            $this,
            'loadConfiguration'
        )
        , 2);
    }

    public function getServiceConfig()
    {
        return array(
            'factories' => array(
                'Admin\Model\UserTable' => function ($sm)
                {
                    $dbAdapter = StaticOptions::getDbReadAdapter();
                    $table = new UserTable($dbAdapter);
                    return $table;
                },
                'AuthAdapter' => function ($sm)
                {
                    $dbAdapter = StaticOptions::getDbReadAdapter();
                    $authAdapter = new AuthAdapterDbTable($dbAdapter, 'admin_users', 'username', 'password', 'md5(?)');
                    return $authAdapter;
                }
            ),
            'invokables' => array(
                'AuthService' => 'Zend\Authentication\AuthenticationService'
            )
        );
    }

    public function loadConfiguration(MvcEvent $e)
    {
        try {
            $application = $e->getApplication();
            $sm = $application->getServiceManager();
            $sharedManager = $application->getEventManager()->getSharedManager();
            
            $sharedManager->attach('Zend\Mvc\Controller\AbstractActionController', 'dispatch', function ($e) use($sm)
            {
                $sm->get('ControllerPluginManager')
                    ->get('AuthenticationPlugin')
                    ->doAuthorization($e);
            });
        } catch (\Exception $ex) {
            echo $ex->getMessage();
        }
    }
}
