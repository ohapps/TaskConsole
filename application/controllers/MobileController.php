<?php

/**
 * 
 * Mobile Controller
 * 
 * This is the controller for the mobile section of the application.
 *  
 * @package TaskConsole
 * @author Craig Hausner 
 * @uses Zend_Loader
 * 
 */
class MobileController extends Zend_Controller_Action {
	
	
	public function init(){
		
		$userManager = Zend_Registry::get('userManager');
		
		if( $userManager->loggedIn() === false ){
			$this->_redirect("/user/login/layout/mobile");		
		}
		
		$this->_helper->layout->setLayout('mobile');			
		
	}
	
	
	public function indexAction(){
							
	}
	
	
	public function notesAction(){		
		
		$data = Array();
    	$user = Zend_Registry::get('user');    	
    	
    	$topics = Doctrine_Core::getTable('Console_Topic')->getByUserId( $user->getUserId() );   	    	    	    	
    	
    	foreach( $topics as $topic ){    		    		
    		
    		$children = array();
    		
    		foreach( $topic->Notes as $note ){
    			    			
				$children[] = array( 					 
					"text" => $note->DESCRIPTION,
					"items" => array(
						array(
							"id" => "note_" . $note->ID,
							"text" => $note->CONTENTS,
							"leaf" => true
						)
					) 					  
				);
				    			
    		}
    		
    		$data[] = array(     			 
    			"text" => $topic->DESCRIPTION,     			     			 
    			"items" => $children 
    		);    	    		
    		
    	}    	    	    	        			
		
		$this->_helper->json->sendJson( array( "items" => $data ) );
		
	}

	
	
}