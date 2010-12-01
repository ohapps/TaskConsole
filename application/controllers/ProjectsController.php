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
    		
    		$priority = Doctrine_Core::getTable('Console_Priority')->find($this->_getParam('PRIORITY_ID'));
    		
    		if( $priority == false ){
    			throw new Exception('invalid priority id');
    		}
    		
    		$project = Doctrine_Core::getTable('Console_Project')->find($this->_getParam('PROJ_ID'));
    		
    		if( $project == false ){
    			throw new Exception('invalid project id');
    		}
    		
    		if( $project->Category->isUserProjectCategory($user->getUserId()) == false ){    			
    			throw new Exception('project category does not belong to current user');
    		}
    		
    		if( $this->_getParam('RECUR_UNIT_TYPE') != '' && in_array( $this->_getParam('RECUR_UNIT_TYPE'), array('days','months','years') ) == false ){
    			throw new Exception('invalid recur unit type');
    		}
    		
    		if( $this->_getParam('ID') != "" ){
    			
    			$task = Doctrine_Core::getTable('Console_Task')->find($this->_getParam('ID'));
    			
    			if( $task == false ){
    				throw new Exception('invalid task id');
    			}
    			
    		}else{
    			$task = new Console_Task();
    		}

    		$task->DESCRIPTION = $this->_getParam('DESCRIPTION');
    		$task->PRIORITY_ID = $priority->ID;
    		$task->PROJ_ID = $project->ID;
    		$task->DUE_DATE = $this->_getParam('DUE_DATE');
    		$task->RECUR_UNIT_TYPE = $this->_getParam('RECUR_UNIT_TYPE');
    		$task->RECUR_UNITS = $this->_getParam('RECUR_UNITS');
    		$task->DISP_ON_GCAL = $this->_getParam('DISP_ON_GCAL');
    		$task->save();    		
    		
	    	$this->_helper->json->sendJson( array( "success" => true ) );
    	}catch( Exception $e ){
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
				
				if( $task->Project->Category->isUserProjectCategory($user->getUserId()) == false ){    			
	    			throw new Exception('task does not belong to current user');
	    		}
				
				$task->delete();
				
			}
			
    		$this->_helper->json->sendJson( array( "success" => true ) );
    	}catch( Exception $e ){
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

					if( $task->Project->Category->isUserProjectCategory($user->getUserId()) == false ){    			
		    			throw new Exception('task does not belong to current user');
		    		}
					
					$task->markComplete($config['date_format']);
												
				}									
							
			}
			
			$this->_helper->json->sendJson( array( "success" => true ) );	
		}catch( Exception $e ){
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
   		$cats = Doctrine_Core::getTable('Console_ProjectCategory')->getByUserId($user->getUserId());		
   		    	
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
	   				"CATEGORY" => $project->CATEGORY,
	   				"COMPLETE" => $project->COMPLETE,
	   				"CAT_DESC" => $project->fullDesc()   			
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
		
		if( $this->_hasParam( 'filter' ) ){
			
			if( is_array( $this->_getParam( 'filter' ) ) ){
			
				foreach( $this->_getParam( 'filter' ) as $filter ){
					
					switch( $filter["field"] ) {
						
					    case "COMPLETE":
					        $options['complete'] = 1;
					    	break;
							
						case "PRIORITY":
							
							if( strpos( $filter["data"]["value"], "Hide High Priority" ) !== false ){
								$options['disp_high'] = 0;
							}
							
							if( strpos( $filter["data"]["value"], "Hide Normal Priority" ) !== false ){
								$options['disp_normal'] = 0;
							}
							
							if( strpos( $filter["data"]["value"], "Hide Low Priority" ) !== false ){
								$options['disp_low'] = 0;
							}															
							
							break;
							
					}	
					
				}	
				
			}
			
		}		    	    	    	
    	
		$tasks = Doctrine_Core::getTable('Console_Task')->getByUserId($user->getUserId(),$options);				
		
		foreach( $tasks as $task ){
			$data[] = array(
				"ID" => $task->ID,
				"PROJ_ID" => $task->Project->ID,
				"PROJECT" => $task->Project->DESCRIPTION,
				"DESCRIPTION" => $task->DESCRIPTION,
				"PRIORITY_ID" => $task->PRIORITY_ID,
				"PRIORITY" => $task->Priority->DESCRIPTION,
				"DUE_DATE" => $task->DUE_DATE,
				"COMPLETE" => $task->isComplete(),
				"COMPLETE_DATE" => $task->COMPLETE_DATE,
				"CATEGORY" => $task->Project->Category->DESCRIPTION
			);
		}
		
		$this->_helper->json->sendJson( array("data" => $data ) ); 				
    	    	    	    	    	    	   
    }
	
	/**
	* returns a json response of high priority tasks for the current user
	*/	 
	public function getUserHighPriorityTasksAction(){
		
		$data = array();
    	$user = Zend_Registry::get('user');

    	$options = array(
			'category' 		=> '',
			'project' 		=> '',
			'complete' 		=> 0,
			'disp_high'		=> 1,
			'disp_normal' 	=> 0,
			'disp_low' 		=> 0				
		);    	
    	
		$tasks = Doctrine_Core::getTable('Console_Task')->getByUserId($user->getUserId(),$options);				
		
		foreach( $tasks as $task ){
			$data[] = array(
				"ID" => $task->ID,
				"PROJ_ID" => $task->Project->ID,
				"PROJECT" => $task->Project->DESCRIPTION,
				"DESCRIPTION" => $task->DESCRIPTION,
				"PRIORITY_ID" => $task->PRIORITY_ID,
				"PRIORITY" => $task->Priority->DESCRIPTION,
				"DUE_DATE" => $task->DUE_DATE,
				"COMPLETE" => $task->isComplete(),
				"COMPLETE_DATE" => $task->COMPLETE_DATE,
				"CATEGORY" => $task->Project->Category->DESCRIPTION
			);
		}
						
		$this->_helper->json->sendJson( array("data" => $data ) );				
		
	}	
	
	/**
	* returns a json response of near due tasks for the current user
	*/	 
	public function getUserNearDueTasksAction(){
		
		$data = array();
    	$user = Zend_Registry::get('user');							

    	$options = array(
			'category' 		=> '',
			'project' 		=> '',
			'complete' 		=> 0,
			'disp_high'		=> 1,
			'disp_normal' 	=> 0,
			'disp_low' 		=> 0,
    		'days_til_due'	=> 3				
		);
    	
		$tasks = Doctrine_Core::getTable('Console_Task')->getByUserId($user->getUserId(),$options);				
		
		foreach( $tasks as $task ){
			$data[] = array(
				"ID" => $task->ID,
				"PROJ_ID" => $task->Project->ID,
				"PROJECT" => $task->Project->DESCRIPTION,
				"DESCRIPTION" => $task->DESCRIPTION,
				"PRIORITY_ID" => $task->PRIORITY_ID,
				"PRIORITY" => $task->Priority->DESCRIPTION,
				"DUE_DATE" => $task->DUE_DATE,
				"COMPLETE" => $task->isComplete(),
				"COMPLETE_DATE" => $task->COMPLETE_DATE,
				"CATEGORY" => $task->Project->Category->DESCRIPTION
			);
		}
    	
		$this->_helper->json->sendJson( array("data" => $data ) );				
		
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
			
			if( $task->Project->Category->isUserProjectCategory($user->getUserId()) == false ){
				throw new Exception('tasks does not belong to current user');
			}
			
			$data = array(
				"ID" => $task->ID,
				"PROJ_ID" => $task->Project->ID,
				"PROJECT" => $task->Project->DESCRIPTION,
				"DESCRIPTION" => $task->DESCRIPTION,
				"PRIORITY_ID" => $task->PRIORITY_ID,
				"PRIORITY" => $task->Priority->DESCRIPTION,
				"DUE_DATE" => $this->_helper->date->format($task->DUE_DATE,$config['date_format']),
				"COMPLETE" => $task->isComplete(),
				"COMPLETE_DATE" => $this->_helper->date->format($task->COMPLETE_DATE,$config['date_format']),
				"CATEGORY" => $task->Project->Category->DESCRIPTION,
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

    	$categories = Doctrine_Core::getTable('Console_ProjectCategory')->getByUserId($user->getUserId());
    	
    	foreach( $categories as $category ){    		    		    		
    		
    		$children = array();
    		
    		foreach( $category->Projects as $project ){
    			
    			if( $project->isActive() ){
	    			$children[] = array( 
	    				"id" => "project_" . $project->ID, 
	    				"text" => $project->DESCRIPTION, 
	    				"leaf" => true, 
	    				"href" => "javascript:loadTasks('". $project->ID ."', '". $category->ID ."')", 
	    				"qtip" => $project->COMMENTS  
	    			);
    			}
    			
    		}
    		
    		if ( count($children) > 0 ){
    			$tree[] = array( 
    				"id" => "category_" . $category->ID, 
    				"text" => $category->DESCRIPTION, 
    				"cls" => "folder", 
    				"expanded" => false, 
    				"href" => "javascript:loadTasks('', '". $category->ID ."')", 
    				"children" => $children 
    			);    	
    		}
    			
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
