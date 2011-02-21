<?php


/**
 * 
 * Project Controller
 * 
 * This is the controller for the projects section of the application.
 *  
 * @package TaskConsole
 * @author Craig Hausner 
 * @uses Zend_Loader
 * 
 */
class ProjectsController extends Zend_Controller_Action 
{
	
	
	public function init(){

		if( $this->_helper->mobile->isIphone() == true ){
			$this->_forward('index','mobile');
		}
		
	}
	
	
	/***************************************
	* LOAD VIEW SCRIPTS
	****************************************/	
	
	/**
	* default action that directs to the main project screen
	*/	 
	public function indexAction() 
    {
     	
    	$user = Zend_Registry::get('user');
    	$gdata = new Oh_Gdata( $user->getUserId() );
     	   
	    if( $gdata->isConnected()== true ){
			$this->view->gdata_enabled = true;
		}else{
			$this->view->gdata_enabled = false;
		}
     	   
    }
    
	
	/***************************************
	* MANAGE PROJECT CATEGORIES
	****************************************/

	/**
	* create or update a project category
	*/	 
	public function savecatAction(){		
    	    	
    	$user = Zend_Registry::get('user');    	    	    			
    	
    	try{
    		
    		if( $this->_getParam('ID') != "" ){
    			$project_category = Doctrine_Core::getTable('Console_ProjectCategory')->find($this->_getParam('ID'));
    			if( $project_category == false ){
    				throw new Exception('invalid project category id');
    			}
    		}else{
    			$project_category = new Console_ProjectCategory();
    		}
    		
    		$project_category->DESCRIPTION = $this->_getParam('DESCRIPTION');
    		$project_category->USER_ID = $user->getUserId();
			$project_category->save();
    		
	    	$this->_helper->json->sendJson( array( "success" => true, "grid_id" => $this->_getParam('GRID_ID'), "id" => $project_category->ID ) );	    	
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );
    	}
    	    	    	    	    	
	}
	
	/**
	* delete a project category
	*/	 
	public function deletecatAction(){		
    	    	
    	$user = Zend_Registry::get('user');    	
    	
    	try{
    		    			    		
    		$project_category = Doctrine_Core::getTable('Console_ProjectCategory')->find($this->_getParam('ID'));
    		
    		if( $project_category == false ){
    			throw new Exception('invalid project category id');
    		}
    		
    		if( $project_category->isUserProjectCategory($user->getUserId()) ){
    			$project_category->delete();	
    		}else{
    			throw new Exception('project category does not belong to current user');
    		}
    		    		
    		$this->_helper->json->sendJson( array( "success" => true ) );    		
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	}						
		
	}	
	
	
	/***************************************
	* MANAGE PROJECTS
	****************************************/	
	
	/**
	* create or update a project
	*/	 
	public function saveprojectAction(){		    	    	    	    	    			
    	
    	try{
    		
    		$user = Zend_Registry::get('user');
    		
    		$project_category = Doctrine_Core::getTable('Console_ProjectCategory')->find($this->_getParam('CATEGORY'));
    		
    		if( $project_category == false ){
    			throw new Exception('invalid project category id');
    		}
    		
    		if( $project_category->isUserProjectCategory($user->getUserId()) == false ){    			
    			throw new Exception('project category does not belong to current user');
    		}
    		
    		if( $this->_getParam('ID') != "" ){
    			
    			$project = Doctrine_Core::getTable('Console_Project')->find($this->_getParam('ID'));
    			
	    		if( $project == false ){
	    			throw new Exception('invalid project id');
	    		}
    			
    		}else{
    			$project = new Console_Project();
    		}
    		
    		$project->DESCRIPTION = $this->_getParam('DESCRIPTION');
    		$project->COMMENTS = $this->_getParam('COMMENTS');
    		$project->CATEGORY = $project_category->ID;
    		$project->COMPLETE = $this->_getParam('COMPLETE');
    		$project->save();
	    		    		    	
	    	$this->_helper->json->sendJson( array( "success" => true, "grid_id" => $this->_getParam('GRID_ID'), "id" => $project->ID ) );	    	
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );	
    	}
    	    	    	    	    
	}		
	
	/**
	* delete a project
	*/	 
	public function deleteprojectAction(){		
    	    	
    	$user = Zend_Registry::get('user');    	
    	
    	try{    		    		
    		
    		$project = Doctrine_Core::getTable('Console_Project')->find($this->_getParam('ID'));
    		
    		if( $project == false ){
    			throw new Exception('invalid project id');
    		}
    		
    		if( $project->Category->isUserProjectCategory($user->getUserId()) ){
    			$project->delete();
    		}else{
    			throw new Exception('project does not belong to current user');
    		}
    		
    		$this->_helper->json->sendJson( array( "success" => true ) );    		
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );
    	}						
		
	}
	
	/***************************************
	* MANAGE TASKS
	****************************************/	
	
	/**
	* create or update a task
	*/	 
	public function savetaskAction(){		    	    				
		
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
    		//$task->DISP_ON_GCAL = $this->_getParam('DISP_ON_GCAL');
    		
    		try{    			    				
    			
    			$task->save();
    			
    			if( $this->_getParam('CATEGORY_ID') != null ){

	    			$category = Doctrine_Core::getTable('Console_Category')->find($this->_getParam('CATEGORY_ID') );
	
					if( $category == false ){
						throw new Exception('invalid category id');
					}
	
					if( $category->isUserCategory($user->getUserId()) == false ){    			
			    		throw new Exception('category does not belong to current user');
			    	}
    				
			    	$task->applyCategory($category);
			    	
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
	public function deletetasksAction(){		    	
				    	
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
	* mark a task complete
	*/	 
	public function markcompleteAction(){				
		
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
	public function markincompleteAction(){				
				
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
	
	
	public function moveTaskInQueueAction(){
		
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('id') ){
			    			    							
				$task = Doctrine_Core::getTable('Console_Task')->find($this->_getParam('id'));

				if( $task == false ){
					throw new Exception('invalid task id');
				}

				if( $task->isUserTask($user->getUserId()) == false ){    			
		    		throw new Exception('task does not belong to current user');
		    	}
					
				$task->moveInQueue($this->_getParam('dir'));																																														
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
			$this->_helper->logger->log()->err( $e->getMessage() );			    				
    		$this->_helper->json->sendJson( array( "success" => false ) );    		
    	} 
		
	}
	
	
	/**
	* apply category to tasks
	*/	 
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
	* JSON QUERIES
	****************************************/		
	
	/**
	* returns a json response with all project categories for the current user
	*/	     
    public function usercatsAction(){    	
    	    	    	    	    
    	$user = Zend_Registry::get('user');   		   		
   		$cats = Doctrine_Core::getTable('Console_Category')->getByUserId($user->getUserId());		
   		    	
    	$this->_helper->json->sendJson( array( "data" => $cats->toArray() ) );
    	
    }
    
    /**
	* returns json response with all projects for the current user
	*/	 
    public function userprojectsAction(){
    	    	    	    	    	  
    	$user = Zend_Registry::get('user');
    	$data = array();
    	$projects = Doctrine_Core::getTable('Console_Project')->getByUserId($user->getUserId());    	   		   		   		   		   		   		
    	
    	foreach($projects as $project){
    		if( $project->isActive() ){
	   			$data[] = array(
					"ID" => $project->ID,
	   				"DESCRIPTION" => $project->DESCRIPTION,
	   				"COMMENTS" => $project->COMMENTS,	   				
	   				"COMPLETED" => $project->COMPLETED 			
	   			);
    		}
   		}
    	
    	$this->_helper->json->sendJson( array("data" => $data ) );    	
    	    	    
    }
	
	/**
	* returns json response with all projects for the current user
	*/	 
    public function userProjectsAllAction(){
    	    	
    	$user = Zend_Registry::get('user');   		   		
   		$data = array();   
   		
   		$projects = Doctrine_Core::getTable('Console_Project')->getByUserId($user->getUserId());
   		
   		foreach($projects as $project){
   			$data[] = array(
				"ID" => $project->ID,
   				"DESCRIPTION" => $project->DESCRIPTION,
   				"COMMENTS" => $project->COMMENTS,
   				"CATEGORY" => $project->CATEGORY,
   				"COMPLETE" => $project->COMPLETE,
   				"CAT_DESC" => $project->fullDesc()   			
   			);
   		}
   		
    	$this->_helper->json->sendJson( array( "data" => $data ) );    	
    	    	    
    }
    
	/**
	* returns json response with all tasks for the current user. the results can be filtered by project, priority and due date.
	*/	     
    public function usertasksAction(){    	    	
    	    	    	    	    	    	    	
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
	* returns a json response of a single task
	*/	 
	public function loadTaskDetailAction(){
				
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
				"RECUR_UNITS" => $task->RECUR_UNITS
			);
			
			$this->_helper->json->sendJson( array( "success" => "true", "data" => $data ) );
		
		}catch(Exception $e){
			$this->_helper->json->sendJson( array( "success" => false ) );
		}
		
	}
	
    /**
	* returns a json response formatted for a list tree with the drill down of project categories and projects for the current user
	*/	 
    public function loadtreeAction(){
    	
    	$tree = Array();
    	$user = Zend_Registry::get('user');    	    	    	    	    	

    	$priorities =  Doctrine_Core::getTable('Console_Priority')->findAll();
    	
    	$children = array();
    	
    	foreach( $priorities as $priority ){
    		
    		$children[] = array( 
	    		"id" => $priority->ID, 
	    		"text" => $priority->DESCRIPTION, 
	    		"leaf" => true, 
    			"checked" => true  
	    	);
    		
    	}
    	
    	if( count($children) > 0 ){
    		
    		$tree[] = array( 
    			"id" => "priority_filter", 
    			"text" => "filter by priority", 
    			"cls" => "folder", 
    			"expanded" => true,     			
    			"children" => $children 
    		);
    		
    	}
    	
    	$categories = Doctrine_Core::getTable('Console_Category')->getByUserId($user->getUserId());    	    	
    	
    	$children = array();
    	
    	if( count( $categories ) > 0 ){
    		
    		$children[] = array( 
	    		"id" => "category_all", 
	    		"text" => "all categories", 
	    		"leaf" => true, 
	    		"href" => "javascript:filterTasks('category', '')", 
	    		"qtip" => "all categories"  
	    	);
    		
    	}
    	
    	foreach( $categories as $category ){    		    		    		
    		    		    		    		
	    	$children[] = array( 
	    		"id" => "category_" . $category->ID, 
	    		"text" => $category->DESCRIPTION, 
	    		"leaf" => true, 
	    		"href" => "javascript:filterTasks('category', '". $category->ID ."')", 
	    		"qtip" => $category->DESCRIPTION  
	    	);    			    			    		    		    		
    			
    	}    	    	
    	
   		if ( count($children) > 0 ){
    		
   			$tree[] = array( 
    			"id" => "category_filter", 
    			"text" => "filter by category", 
    			"cls" => "folder", 
    			"expanded" => true, 
    			//"href" => "javascript:loadTasks('', '". $category->ID ."')", 
    			"children" => $children 
    		);
    		    	
    	}
    	
    	$projects = Doctrine_Core::getTable('Console_Project')->getByUserId($user->getUserId());    	    	    	    	    	    	
    	
    	$children = array();
    	
    	if( count($projects) > 0 ){
    		
    		$children[] = array( 
	    		"id" => "project_all", 
	    		"text" => "all projects", 
	    		"leaf" => true, 
	    		"href" => "javascript:filterTasks('project', '')", 
	    		"qtip" => "all projects"  
	    	);
    		
    	}
    	
    	foreach( $projects as $project ){    		    		    		
    		    		    		    		
	    	$children[] = array( 
	    		"id" => "project_" . $project->ID, 
	    		"text" => $project->DESCRIPTION, 
	    		"leaf" => true, 
	    		"href" => "javascript:filterTasks('project', '". $project->ID ."')", 
	    		"qtip" => $project->COMMENTS  
	    	);    			    			    		    		    		
    			
    	}    	    	
    	
   		if ( count($children) > 0 ){
    		
   			$tree[] = array( 
    			"id" => "project_filter", 
    			"text" => "filter by project", 
    			"cls" => "folder", 
    			"expanded" => true, 
    			//"href" => "javascript:loadTasks('', '". $category->ID ."')", 
    			"children" => $children 
    		);
    		    	
    	}    	
    	
    	$this->_helper->json->sendJson( $tree );
    	
    }

    
	/**
	* returns a json response of the available priorities for a task
	*/	 
	public function prioritiesAction(){		
    	    	
    	$priorities =  Doctrine_Core::getTable('Console_Priority')->findAll();
    	    	
		$this->_helper->json->sendJson( array( "data" => $priorities->toArray() ) );		
		
	}
	
	
}

?>
