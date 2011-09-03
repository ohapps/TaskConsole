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

    	$user = Zend_Registry::get('user');   		   		
   		$this->view->cats = Doctrine_Core::getTable('Console_Category')->getByUserId($user->getUserId());
   		$this->view->priorities = Doctrine_Core::getTable('Console_Priority')->findAll();
    	
    }
	
    
    public function settingsAction(){
    	
    	
    	
    }
    
	
}
