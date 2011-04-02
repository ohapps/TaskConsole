<?php


/**
 * Index Controller 
 * 
 * The default controller class
 * 
 * @package TaskConsole
 * @author Craig Hausner 
 * @uses Zend_Loader 
 */
class IndexController extends Zend_Controller_Action {

	
	public function init(){
				
		$userManager = Zend_Registry::get('userManager');
		
		if( $userManager->loggedIn() === false ){
			$this->_redirect("/user/login");		
		}										
		
	}
	
	
	/**
	 * The default action - show the home page
	 */
    public function indexAction(){    	    	    	
    	
    }
	
	
}
