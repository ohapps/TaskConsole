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
			$this->_redirect("/user/login");		
		}
		
		$this->_helper->layout->setLayout('mobile');			
		
	}
	
	
	public function indexAction(){
							
	}
	
	
	public function projectsAction(){
		
		$user = Zend_Registry::get('user');
		$this->view->categories = Doctrine_Core::getTable('Console_ProjectCategory')->getByUserId($user->getUserId());
		
	}
	
	
	public function tasksAction(){
		
		$user = Zend_Registry::get('user');    	
		
		$options = array(
			'category' 		=> '',
			'project' 		=> '',
			'complete' 		=> 0,
			'disp_high'		=> 1,
			'disp_normal' 	=> 1,
			'disp_low' 		=> 1				
		);    	    	    	     	          	    	
		
		if( $this->_hasParam( 'category' ) ){
			$options['category'] = $this->_getParam('category');
		}  	    	
    	 
		if( $this->_hasParam( 'project' ) ){
			$options['project'] = $this->_getParam('project');
		}
		
		$this->view->category = $this->_getParam('category');
		$this->view->project = $this->_getParam('project');
		$this->view->tasks = Doctrine_Core::getTable('Console_Task')->getByUserId($user->getUserId(),$options);	
				
	}
	
	
	public function taskDetailAction(){

		$user = Zend_Registry::get('user');
		$task = Doctrine_Core::getTable('Console_Task')->find( $this->_getParam('id') );		
		
		if( $task == false ){
			throw new Exception('invald task id when view task detail');	
		}
		
		if( $task->Project->Category->isUserProjectCategory($user->getUserId()) == false ){    			
    		throw new Exception('task does not belong to current user');
    	}
		
		$this->view->category = $this->_getParam('category');
		$this->view->project = $this->_getParam('project');
		$this->view->task = $task;
		
	}
	
	
	public function markTaskCompleteAction(){
		
		$config = $this->getInvokeArg('bootstrap')->getOption('app');		
		$user = Zend_Registry::get('user');
		$task = Doctrine_Core::getTable('Console_Task')->find( $this->_getParam('id') );		
		
		if( $task == false ){
			throw new Exception('invald task id when view task detail');	
		}
		
		if( $task->Project->Category->isUserProjectCategory($user->getUserId()) == false ){    			
    		throw new Exception('task does not belong to current user');
    	}
										
		$task->markComplete($config['date_format']);
			
		$this->_forward( 'tasks', null, null, array( 'category' => $this->_getParam('category'), 'project' => $this->_getParam('project') ) );
		
	}
	
	
	public function notesAction(){
		
		$user = Zend_Registry::get('user');
		$this->view->topics = Doctrine_Core::getTable('Console_Topic')->getByUserId( $user->getUserId() );
		
	}
	
	
	public function viewNoteAction(){
		
		$user = Zend_Registry::get('user');
		$note = Doctrine_Core::getTable('Console_Note')->find($this->_getParam('id'));
		
		if( $note == false ){
			throw new Exception('invalid note id when viewing note');
		}
		
		if( $note->Topic->isUserTopic($user->getUserId()) == false ){
			throw new Exception('note belong to another user when viewing note');
		}
		
		$this->view->note = $note;
		
	}
		
	
}