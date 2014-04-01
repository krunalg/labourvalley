<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2014 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */
namespace Application;

use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Commons\StaticOptions;

class Module extends \Commons\Module
{
    protected $_namespace = __NAMESPACE__;
    protected $_dir = __DIR__;
    
    public function onBootstrap(MvcEvent $e)
    {
        $eventManager = $e->getApplication()->getEventManager();
        $moduleRouteListener = new ModuleRouteListener();
        $moduleRouteListener->attach($eventManager);
        
        /**
		 * Get the module manager for the current Application
		 *
		 * @var \Zend\ModuleManager\ModuleManager $moduleManager
		 */
		$moduleManager = $e->getApplication ()->getServiceManager ()->get ( 'modulemanager' );
		/**
		 * Get the set of shared events
		 *
		 * @var \Zend\EventManager\SharedEventManager $sharedEvents
		 */
		$sharedEvents = $moduleManager->getEventManager ()->getSharedManager ();
		
		/**
		 * Add Service Locator to Static Options
		 */
		$sharedEvents->attach ( 'Zend\Mvc\Controller\AbstractController', MvcEvent::EVENT_DISPATCH, array (
				$this,
				'addServiceLocator' 
		), 997 );
		/**
		 * Initialize Constants before moving forward
		 */
		$sharedEvents->attach ( 'Zend\Mvc\Controller\AbstractController', MvcEvent::EVENT_DISPATCH, array (
				$this,
				'initConstants' 
		), 999 );
    }

    /**
     * Adding service locator to the static options so that its accessible form anywhere
     *
     * @param MvcEvent $e
     */
    public function addServiceLocator(MvcEvent $e) {
        return StaticOptions::setServiceLocator ( $e->getTarget ()->getServiceLocator () );
    }
    public function initConstants(MvcEvent $e) {
        $sl = $e->getApplication ()->getServiceManager ();
        $config = $sl->get ( 'Config' );
        $constants = function ($search_term) use($config) {
            $currTarget = $config ['constants'];
            $kArr = explode ( ":", $search_term );
            	
            foreach ( $kArr as $key => $value ) {
                if (isset ( $currTarget [$value] )) {
                    $currTarget = $currTarget [$value];
                } else {
                    throw new \Exception ( 'Invalid Configuration Path: ' . $search_term . ' in $config["constants"]' );
                }
            }
            return $currTarget;
        };
        return false;
    }
}
