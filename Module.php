<?php
/**
 * Zend Framework (http://framework.zend.com/)
 *
 * @link      http://github.com/zendframework/ZendSkeletonApplication for the canonical source repository
 * @copyright Copyright (c) 2005-2013 Zend Technologies USA Inc. (http://www.zend.com)
 * @license   http://framework.zend.com/license/new-bsd New BSD License
 */

namespace Confi;

use Zend\ModuleManager\Feature\AutoloaderProviderInterface; 
use Zend\Mvc\ModuleRouteListener;
use Zend\Mvc\MvcEvent;
use Zend\Authentication\AuthenticationService;

class Module
{
    public function onBootstrap(MvcEvent $e)
    {
        $translator          = $e->getApplication()->getServiceManager()->get('translator'); 
        $eventManager        = $e->getApplication()->getEventManager(); 
        $moduleRouteListener = new ModuleRouteListener(); 
        $moduleRouteListener->attach($eventManager); 
        $application         = $e->getApplication(); 
        // Cambio de idioma en las validaciones
        $translator->addTranslationFile( 
            'phpArray', 
            'vendor/zendframework/zendframework/resources/languages/Zend_Validate.php', 
            'default', 
            'de_DE' 
        ); 
       $translator->addTranslationFile( 
            'phpArray', 
            './vendor/zendframework/zendframework/resources/languages/es/Zend_Validate.php' 
       );
       // Validar session
       $eventManager->attach(MvcEvent::EVENT_DISPATCH, array($this, 'authPreDispatch'),1);
    }

    public function authPreDispatch($event)
    {
       // Direccionar al login 
       $auth = new AuthenticationService();
       if (!$auth->hasIdentity()) 
       {
	  // echo 'no';
       }		        
    }
 
    public function getConfig()
    {
        return include __DIR__ . '/config/module.config.php';
    }

    public function getAutoloaderConfig()
    {
        return array(
            'Zend\Loader\StandardAutoloader' => array(
                'namespaces' => array(
                    __NAMESPACE__ => __DIR__ . '/src/' . __NAMESPACE__,
                ),
            ),
        );
    }
}