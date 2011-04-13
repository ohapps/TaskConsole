<?php

class Bootstrap extends Zend_Application_Bootstrap_Bootstrap
{

	
	public function _initDoctrine()
	{		
		
		if( $this->hasOption('doctrine') ){
			
			$doctrineConfig = $this->getOption('doctrine');
		
			require_once 'Doctrine.php';        
		    $loader = Zend_Loader_Autoloader::getInstance();
		    $loader->pushAutoloader(array('Doctrine', 'autoload'));	 	   		   	    	    
		 
		    $manager = Doctrine_Manager::getInstance();
		    $manager->setAttribute(Doctrine::ATTR_MODEL_LOADING,Doctrine::MODEL_LOADING_CONSERVATIVE);	        
		    $manager->setAttribute(Doctrine_Core::ATTR_AUTOLOAD_TABLE_CLASSES,true);    
		    $manager->setAttribute(Doctrine_Core::ATTR_VALIDATE, Doctrine_Core::VALIDATE_ALL & ~Doctrine_Core::VALIDATE_TYPES );
		 
		    // Add models and generated base classes to Doctrine autoloader
		    Doctrine::loadModels($doctrineConfig['models_path']);
		 
		    $manager->openConnection($doctrineConfig['connection_string']);    
		 
		    return $manager;
	    
		}
	    
	}		
	
		
    protected function _initUser(){
		
		$config = $this->getOption('oh');				
		
		$userManager = new Oh_UserManager();
		$user = $userManager->getCurrentUser();
		
		/*
		if( $user === false && $config['login_required'] == true ){					    			
			$userManager->chk_auth( $config['login_url'] );					
		}
		*/
		
		Zend_Registry::set('userManager',$userManager);
		Zend_Registry::set('user',$user);
		
		return $user;
		
	}    
	
	
	protected function _initView()
    {
        // Initialize view
        $view = new Zend_View();        

        // Add it to the ViewRenderer
        $viewRenderer = Zend_Controller_Action_HelperBroker::getStaticHelper('ViewRenderer');
        $viewRenderer->setView($view);

        // SET VIEW VARIABLES
        if( $this->hasResource('user') ){
	        $user = $this->getResource('user'); 
	        if( $user != false ){       
		        $view->user = $user;				
				$view->logout_url = $user->getManager()->getLogoutUrl();
	        }
        }
        
        // Return it, so that it can be stored by the bootstrap
        return $view;
    }	
    
    
    protected function _initHelpers(){
    	
    	// ACTION HELPERS
    	Zend_Controller_Action_HelperBroker::addPrefix('Oh_Action_Helper');
    	
    	// VIEW HELPERS
    	$view = $this->getResource('view');
    	$view->addHelperPath( dirname(__FILE__) . '/View/Helper', 'Oh_View_Helper' );
    	
    }
    
    
	protected function _initLogger(){
		
		// SET LOGGING
		$logger = new Oh_Log();
		Zend_Registry::set('logger', $logger);
		
		return $logger;
		
	}
    
    
}