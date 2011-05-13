<?php

/**
 * 
 * Task Controller
 * 
 * This is the controller for the task section of the application.
 *  
 * @package TaskConsole
 * @author Craig Hausner 
 * @uses Zend_Loader
 * 
 */
class TasksController extends Zend_Controller_Action {
	
	
	public function init(){
		
		$userManager = Zend_Registry::get('userManager');
		
		if( $userManager->loggedIn() === false ){
			$this->_redirect("/user/login");		
		}
		
	}
	
	
	public function saveAction(){		    	    				
		
    	try{
    		
    		$user = Zend_Registry::get('user');    		    		
    		
    		if( $this->_getParam('ID') != "" ){
    			
    			$task = Doctrine_Core::getTable('Console_Task')->find($this->_getParam('ID'));
    			
    			if( $task == false ){
    				throw new Exception('invalid task id');
    			}
    			
    		}else{
    			$task = new Console_Task();    			
    		}
    		
    		$task->USER_ID = $user->getUserId();
    		$task->DESCRIPTION = $this->_getParam('DESCRIPTION');
    		$task->PRIORITY_ID = $this->_getParam('PRIORITY_ID');    		
    		$task->PROJECT_ID = $this->_getParam('PROJECT_ID');
    		$task->DUE_DATE = $this->_getParam('DUE_DATE');
    		$task->RECUR_UNIT_TYPE = $this->_getParam('RECUR_UNIT_TYPE');
    		$task->RECUR_UNITS = $this->_getParam('RECUR_UNITS');    		
    		
    		try{    			    				
    			
    			$task->save();    			    			
    			
    			if( $this->_getParam('CATEGORIES') != null ){    				
    				$categories = explode(',',$this->_getParam('CATEGORIES'));    				    				    				    				    				
    			}else{
    				$categories = array();
    			}
    			
    			$task->setCategoriesFromArray($categories);
    			    			
    			if( $this->_getParam('ADD_TO_QUEUE') == '1' ){
    				$task->addToQueue();
    			}
    			
    			$this->_helper->json->sendJson( array( "success" => true ) );
    		}catch( Doctrine_Validator_Exception $e ){    		    		
    			$this->_helper->json->sendJson( array( "success" => false, "errors" => $task->getErrorStack()->toArray() ) );	
    		}	    		    		
	    	
    	}catch( Exception $e ){
    		$this->_helper->logger->log()->err( $e->getMessage() );    		    		
    		$this->_helper->json->sendJson( array( "success" => false ) );	
    	}
    	    	    	    	
	}	

	
	/**
	* delete a task
	*/	 
	public function deleteAction(){		    	
				    	
    	try{

    		$user = Zend_Registry::get('user');
    		
    		if(is_array($this->_getParam('data'))){ 
	    		$ids = $this->_getParam('data');
			}else{
				$ids = array($this->_getParam('data'));		
			}

			foreach( $ids as $id ){
				
				$task = Doctrine_Core::getTable('Console_Task')->find($id);
				
				if( $task == false ){
					throw new Exception('invalid task id');	
				}
				
				if( $task->isUserTask($user->getUserId()) == false ){    			
	    			throw new Exception('task does not belong to current user');
	    		}
				
				$task->delete();
				
			}
			
    		$this->_helper->json->sendJson( array( "success" => true ) );
    	}catch( Exception $e ){
    		$this->_helper->logger->log()->err( $e->getMessage() );
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	}						
		
	}			
	
	
	/**
	* returns a json response of a single task
	*/	 
	public function loadAction(){
				
		$user = Zend_Registry::get('user');
		$config = $this->getInvokeArg('bootstrap')->getOption('app');						
		
		try{
		
			if( $this->_hasParam('id') == false ){
				throw new Exception('missing id parameter');												
			}
			
			$task = Doctrine_Core::getTable('Console_Task')->find( $this->_getParam('id') );
			
			if( $task == false ){
				throw new Exception('invalid task id');
			}
			
			if( $task->isUserTask($user->getUserId()) == false ){
				throw new Exception('tasks does not belong to current user');
			}
			
			$data = array(
				"ID" => $task->ID,
				"PROJECT_ID" => $task->PROJECT_ID,				
				"DESCRIPTION" => $task->DESCRIPTION,
				"PRIORITY_ID" => $task->PRIORITY_ID,				
				"DUE_DATE" => $this->_helper->date->format($task->DUE_DATE,$config['date']['appFormat']),				
				"COMPLETED" => $this->_helper->date->format($task->COMPLETED,$config['date']['appFormat']),				
				"RECUR_UNIT_TYPE" => $task->RECUR_UNIT_TYPE,
				"RECUR_UNITS" => $task->RECUR_UNITS,
				"ADD_TO_QUEUE" => $task->isQueued(),
				"CATEGORIES" => $task->categoryIdsList()
			);
			
			$this->_helper->json->sendJson( array( "success" => "true", "data" => $data ) );
		
		}catch(Exception $e){
			$this->_helper->json->sendJson( array( "success" => false ) );
		}
		
	}
	
	
	/**
	* returns json response with all tasks for the current user. the results can be filtered by project, priority and due date.
	*/	     
    public function userTasksAction(){    	    	
    	    	    	    	    	    	    	
    	$data = array();
    	$user = Zend_Registry::get('user');
    	
    	if( $this->_hasParam('start') === false || $this->_hasParam('limit') === false ){
    		throw new Exception('missing start or limit parameter when loading user tasks');
    	}
    	
    	if( is_numeric( $this->_getParam('start') ) === false || is_numeric($this->_getParam('limit')) === false ){
    		throw new Exception('start or limit parameter is not a numeric value when loading user tasks');
    	}
    	
		$limit = $this->_getParam('limit');
        $start = ( ( $this->_getParam('start') / $limit ) + 1 );    			    			    	    	    	     	          	   				    	    	    
    	
		$pager = Doctrine_Core::getTable('Console_Task')->getPagedResultsByUserId(
			$user->getUserId(),
			array(
				"category" => $this->_getParam('category'),
				"project" => $this->_getParam('project'),
				"sort" => $this->_getParam('sort'),
				"dir" => $this->_getParam('dir'),
				"status" => $this->_getParam('status'),
				"priorities" => explode(",",$this->_getParam('priorities')),
				"keyword" => $this->_getParam('keyword')			
			),
			$start,
			$limit			
		);

		$tasks = $pager->execute();
        $total = $pager->getNumResults();		
		
		foreach( $tasks as $task ){						
			
			$data[] = array(
				"ID" => $task->ID,
				"PROJECT_ID" => $task->PROJECT_ID,
				"PROJECT" => ( $task->PROJECT_ID != null )? $task->Project->DESCRIPTION : "",				
				"DESCRIPTION" => $task->DESCRIPTION,
				"PRIORITY_ID" => $task->PRIORITY_ID,
				"PRIORITY" => $task->Priority->DESCRIPTION,
				"DUE_DATE" => $task->DUE_DATE,				
				"COMPLETED" => $task->COMPLETED,
				"DISPLAY_DATE" => $task->DISPLAY_DATE,
				"QUEUE_ORDER" => $task->QUEUE_ORDER,
				"CATEGORIES" => $task->categoryList()
			);
		
		}
		
		$this->_helper->json->sendJson( array( "results" => $total, "data" => $data ) ); 				
    	    	    	    	    	    	   
    }
	
	
	/**
	* mark a task complete
	*/	 
	public function markCompleteAction(){				
		
		$config = $this->getInvokeArg('bootstrap')->getOption('app');
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('data') ){
			    		
	    		if(is_array($this->_getParam('data'))){ 
		    		$ids = $this->_getParam('data');
				}else{
					$ids = array($this->_getParam('data'));		
				}																				
				
				foreach( $ids as $id ){
					
					$task = Doctrine_Core::getTable('Console_Task')->find($id);

					if( $task == false ){
						throw new Exception('invalid task id');
					}

					if( $task->isUserTask($user->getUserId()) == false ){    			
		    			throw new Exception('task does not belong to current user');
		    		}
					
					$task->markComplete($config['date']['dbFormat']);																					
												
				}									
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
			$this->_helper->logger->log()->err( $e->getMessage() );			    				
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	} 
						
	}
	
	
	/**
	* mark a task complete
	*/	 
	public function markIncompleteAction(){				
				
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('data') ){
			    		
	    		if(is_array($this->_getParam('data'))){ 
		    		$ids = $this->_getParam('data');
				}else{
					$ids = array($this->_getParam('data'));		
				}																				
				
				foreach( $ids as $id ){
					
					$task = Doctrine_Core::getTable('Console_Task')->find($id);

					if( $task == false ){
						throw new Exception('invalid task id');
					}

					if( $task->isUserTask($user->getUserId()) == false ){    			
		    			throw new Exception('task does not belong to current user');
		    		}
					
					$task->markIncomplete();																					
												
				}									
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
			$this->_helper->logger->log()->err( $e->getMessage() );			    				
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	} 
						
	}
	
	
	public function applyCategoryAction(){				
				
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('data') && $this->_hasParam('id') ){

				$category = Doctrine_Core::getTable('Console_Category')->find($this->_getParam('id') );

				if( $category == false ){
					throw new Exception('invalid category id');
				}

				if( $category->isUserCategory($user->getUserId()) == false ){    			
		    		throw new Exception('category does not belong to current user');
		    	}				
				
	    		if(is_array($this->_getParam('data'))){ 
		    		$ids = $this->_getParam('data');
				}else{
					$ids = array($this->_getParam('data'));		
				}																				
				
				foreach( $ids as $id ){
					
					$task = Doctrine_Core::getTable('Console_Task')->find($id);

					if( $task == false ){
						throw new Exception('invalid task id');
					}

					if( $task->isUserTask($user->getUserId()) == false ){    			
		    			throw new Exception('task does not belong to current user');
		    		}
					
					$task->applyCategory($category);																					
												
				}									
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
			$this->_helper->logger->log()->err( $e->getMessage() );			    				
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	} 
						
	}
	
	
	/**
	* remove category to tasks
	*/	 
	public function removeCategoryAction(){				
				
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('data') && $this->_hasParam('id') ){

				$category = Doctrine_Core::getTable('Console_Category')->find($this->_getParam('id') );

				if( $category == false ){
					throw new Exception('invalid category id');
				}

				if( $category->isUserCategory($user->getUserId()) == false ){    			
		    		throw new Exception('category does not belong to current user');
		    	}				
				
	    		if(is_array($this->_getParam('data'))){ 
		    		$ids = $this->_getParam('data');
				}else{
					$ids = array($this->_getParam('data'));		
				}																				
				
				foreach( $ids as $id ){
					
					$task = Doctrine_Core::getTable('Console_Task')->find($id);

					if( $task == false ){
						throw new Exception('invalid task id');
					}

					if( $task->isUserTask($user->getUserId()) == false ){    			
		    			throw new Exception('task does not belong to current user');
		    		}
					
					$task->removeCategory($category);																					
												
				}									
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
			$this->_helper->logger->log()->err( $e->getMessage() );			    				
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	} 
						
	}

	
	/***************************************
	* MANAGE QUEUE
	****************************************/
	public function addToQueueAction(){
		
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('data') ){
			    		
	    		if(is_array($this->_getParam('data'))){ 
		    		$ids = $this->_getParam('data');
				}else{
					$ids = array($this->_getParam('data'));		
				}																				
				
				foreach( $ids as $id ){
					
					$task = Doctrine_Core::getTable('Console_Task')->find($id);

					if( $task == false ){
						throw new Exception('invalid task id');
					}

					if( $task->isUserTask($user->getUserId()) == false ){    			
		    			throw new Exception('task does not belong to current user');
		    		}
					
					$task->addToQueue();																					
												
				}									
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
			$this->_helper->logger->log()->err( $e->getMessage() );			    				
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	} 
		
	}
	
	
	public function removeFromQueueAction(){
		
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('data') ){
			    		
	    		if(is_array($this->_getParam('data'))){ 
		    		$ids = $this->_getParam('data');
				}else{
					$ids = array($this->_getParam('data'));		
				}																				
				
				foreach( $ids as $id ){
					
					$task = Doctrine_Core::getTable('Console_Task')->find($id);

					if( $task == false ){
						throw new Exception('invalid task id');
					}

					if( $task->isUserTask($user->getUserId()) == false ){    			
		    			throw new Exception('task does not belong to current user');
		    		}
					
					$task->removeFromQueue();																					
												
				}									
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
			$this->_helper->logger->log()->err( $e->getMessage() );			    				
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	} 
		
	}			
	
	
}	