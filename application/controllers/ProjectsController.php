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
		
		$userManager = Zend_Registry::get('userManager');
		
		if( $userManager->loggedIn() === false ){
			$this->_redirect("/user/login");		
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
    			
    			$category = Doctrine_Core::getTable('Console_Category')->find($this->_getParam('ID'));
    			
    			if( $category == false ){
    				throw new Exception('invalid category id');
    			}
    			
    			if( $category->isUserCategory($user->getUserId()) === false ){
    				throw new Exception('category does not belong to current user');
    			}
    			
    		}else{
    			$category = new Console_Category();
    		}
    		
    		$category->DESCRIPTION = $this->_getParam('DESCRIPTION');
    		$category->USER_ID = $user->getUserId();
			$category->save();
    		
	    	$this->_helper->json->sendJson( array( "success" => true, "grid_id" => $this->_getParam('GRID_ID'), "id" => $category->ID ) );	    	
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
    		    			    		
    		$category = Doctrine_Core::getTable('Console_Category')->find($this->_getParam('ID'));
    		
    		if( $category == false ){
    			throw new Exception('invalid category id');
    		}
    		
    		if( $category->isUserCategory($user->getUserId()) ){
    			$category->delete();	
    		}else{
    			throw new Exception('category does not belong to current user');
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
    		
    		if( $this->_getParam('ID') != "" ){
    			
    			$project = Doctrine_Core::getTable('Console_Project')->find($this->_getParam('ID'));
    			
	    		if( $project == false ){
	    			throw new Exception('invalid project id');
	    		}
	    		
	    		if( $project->isUserProject($user->getUserId()) === false ){
	    			throw new Exception('project does not belong to current user');
	    		}
    			
    		}else{
    			$project = new Console_Project();
    			$project->USER_ID = $user->getUserId();
    		}
    		
    		$project->DESCRIPTION = $this->_getParam('DESCRIPTION');
    		$project->COMMENTS = $this->_getParam('COMMENTS');    		
    		$project->AUTO_COMPLETE = $this->_getParam('AUTO_COMPLETE');
    		$project->save();
    		
    		if( $this->_getParam('STATUS') == 'complete' ){
    			
    			$config = $this->getInvokeArg('bootstrap')->getOption('app');    			
    			$project->markComplete($config['date']['dbFormat']);
    			
    		}else if( $this->_getParam('STATUS') == 'active' && $project->isComplete() === true ){
    			
    			$project->markIncomplete();
    			
    		}

    		if( $this->_getParam('CATEGORY_ID') != null ){

	    			$category = Doctrine_Core::getTable('Console_Category')->find($this->_getParam('CATEGORY_ID') );
	
					if( $category == false ){
						throw new Exception('invalid category id');
					}
	
					if( $category->isUserCategory($user->getUserId()) == false ){    			
			    		throw new Exception('category does not belong to current user');
			    	}
    				
			    	$project->applyCategory($category);
			    	
    		}
    		
	    	$this->_helper->json->sendJson( array( "success" => true, "id" => $project->ID ) );	    	
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false, "error" => $e->getMessage()) );	
    	}
    	    	    	    	    
	}		
	
	/**
	* delete a project
	*/	 
	public function deleteProjectAction(){		
    	    	
    	$user = Zend_Registry::get('user');    	
    	
    	try{    		    		
    		
    		$project = Doctrine_Core::getTable('Console_Project')->find($this->_getParam('id'));
    		
    		if( $project == false ){
    			throw new Exception('invalid project id');
    		}
    		
    		if( $project->isUserProject($user->getUserId()) === true ){
    			$project->delete();
    		}else{
    			throw new Exception('project does not belong to current user');
    		}
    		
    		$this->_helper->json->sendJson( array( "success" => true ) );    		
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );
    	}						
		
	}
	
	
	/**
	* returns a json response of a single task
	*/	 
	public function loadProjectAction(){
				
		$user = Zend_Registry::get('user');
		$config = $this->getInvokeArg('bootstrap')->getOption('app');						
		
		try{
		
			if( $this->_hasParam('id') == false ){
				throw new Exception('missing id parameter');												
			}
			
			$project = Doctrine_Core::getTable('Console_Project')->find( $this->_getParam('id') );
			
			if( $project == false ){
				throw new Exception('invalid project id');
			}
			
			if( $project->isUserProject($user->getUserId()) == false ){
				throw new Exception('project does not belong to current user');
			}
			
			$data = array(
				"ID" => $project->ID,							
				"DESCRIPTION" => $project->DESCRIPTION,
				"COMMENTS" => $project->COMMENTS,												
				"STATUS" => $project->getStatus(),				
				"AUTO_COMPLETE" => $project->AUTO_COMPLETE
			);
			
			$this->_helper->json->sendJson( array( "success" => "true", "data" => $data ) );
		
		}catch(Exception $e){
			$this->_helper->json->sendJson( array( "success" => false ) );
		}
		
	}
	
	
	/***************************************
	* MANAGE TASKS
	****************************************/	
	
	/**
	* create or update a task
	*/	 
	
	
	
	
	
	
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
					
					$project = Doctrine_Core::getTable('Console_Project')->find($id);

					if( $project == false ){
						throw new Exception('invalid project id');
					}

					if( $project->isUserProject($user->getUserId()) == false ){    			
		    			throw new Exception('project does not belong to current user');
		    		}
					
					$project->applyCategory($category);																					
												
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
					
					$project = Doctrine_Core::getTable('Console_Project')->find($id);

					if( $project == false ){
						throw new Exception('invalid project id');
					}

					if( $project->isUserProject($user->getUserId()) == false ){    			
		    			throw new Exception('project does not belong to current user');
		    		}
					
					$project->removeCategory($category);																					
												
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
    public function userProjectsPagedAction(){
    	    	
    	$user = Zend_Registry::get('user');   		   		
   		$data = array();      		
   		
   		if( $this->_hasParam('start') === false || $this->_hasParam('limit') === false ){
    		throw new Exception('missing start or limit parameter when loading user projects');
    	}
    	
    	if( is_numeric( $this->_getParam('start') ) === false || is_numeric($this->_getParam('limit')) === false ){
    		throw new Exception('start or limit parameter is not a numeric value when loading user projects');
    	}
    	
		$limit = $this->_getParam('limit');
        $start = ( ( $this->_getParam('start') / $limit ) + 1 );    			    			    	    	    	     	          	   				    	    	    
    	
		$pager = Doctrine_Core::getTable('Console_Project')->getPagedResultsByUserId(
			$user->getUserId(),
			array(				
				"sort" => $this->_getParam('sort'),
				"dir" => $this->_getParam('dir'),
				"status" => $this->_getParam('status'),
				"category" => $this->_getParam('category')
			),
			$start,
			$limit			
		);

		$projects = $pager->execute();
        $total = $pager->getNumResults();		
		
    	foreach($projects as $project){
   			$data[] = array(
				"ID" => $project->ID,
   				"DESCRIPTION" => $project->DESCRIPTION,
   				"COMMENTS" => $project->COMMENTS,
   				"COMPLETED" => $project->COMPLETED,
   				"STATUS" => $project->getStatus(),
   				"TASK_PENDING" => $project->getTaskPendingCount(),
   				"TASK_COMPLETE" => $project->getTaskCompleteCount(),
   				"TASK_TOTAL" => $project->getTaskTotal(),
	   			"CATEGORIES" => $project->categoryList()  			
   			);
   		}
		
		$this->_helper->json->sendJson( array( "results" => $total, "data" => $data ) );    		     	
    	    	    
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
    	
    	$projects = Doctrine_Core::getTable('Console_Project')->getByUserId($user->getUserId(),'active');    	    	    	    	    	    	
    	
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
