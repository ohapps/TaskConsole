<?php

class Console_Task extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('tasks');
		$this->hasColumn('ID', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('USER_ID', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
    	$this->hasColumn('PROJECT_ID', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             //'unsigned' => 1,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));	
		$this->hasColumn('DESCRIPTION', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('PRIORITY_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
     	$this->hasColumn('DUE_DATE', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));            
        $this->hasColumn('COMPLETED', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('RECUR_UNIT_TYPE', 'string', null, array(
             'type' => 'string',
        	 'length' => 25,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,        	 
             ));
        $this->hasColumn('RECUR_UNITS', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,             
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('DISPLAY_DATE', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('ORIG_ID', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));             
        $this->hasColumn('GCAL_ID', 'string', null, array(
             'type' => 'string',
        	 'length' => 255,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('QUEUE_ORDER', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
	}

	
	public function setUp(){
    	$this->hasOne('Console_Project as Project', array(
                'local' => 'PROJECT_ID',
                'foreign' => 'ID'
            )
        );
        $this->hasOne('Console_Priority as Priority', array(
                'local' => 'PRIORITY_ID',
                'foreign' => 'ID'
            )
        );
        $this->hasMany('Console_Category as Categories', array(
                'local' => 'TASK_ID',
                'foreign' => 'CATEGORY_ID',
                'refClass' => 'Console_TaskCategory'
            )
        );        
    }
	
    
    public function preSave($event){
    	
    	/*
    	if($this->PROJECT_ID == 0){
    		$this->PROJECT_ID = null;
    	}
    	*/
    	
    }
    
    
    protected function validate(){
    	
    	$errorStack = $this->getErrorStack();
    	
    	$priority = Doctrine_Core::getTable('Console_Priority')->find($this->PRIORITY_ID);
    		
    	if( $priority == false ){    	    	    		
            $errorStack->add('PRIORITY_ID', 'invalid priority');
    	}
    	
    	if( $this->PROJECT_ID != null ){
    	
	    	$project = Doctrine_Core::getTable('Console_Project')->find($this->PROJECT_ID);
	    		
	    	if( $project == false ){
	    		$errorStack->add('PROJECT_ID', 'invalid project');	    	
	    	}
    	
    	}
    	
    	if( $this->RECUR_UNIT_TYPE != '' && in_array( $this->RECUR_UNIT_TYPE, array('days','months','years') ) == false ){
    		$errorStack->add('RECUR_UNIT_TYPE', 'invalid recurring unit type');
    	}
    	
    }
    
    
    public function markComplete($format){
    	
    	if( $this->COMPLETED == null || $this->COMPLETED == '' ){
    		
    		$today = new Zend_Date();
    			    	
	    	$this->COMPLETED = $today->toString($format);	    	
	    	$this->save();

	    	// CHECK IF A RECURRING TASK IS REQUIRED	    	
	    	if( $this->RECUR_UNIT_TYPE != "" && $this->RECUR_UNITS != "" && $this->RECUR_UNITS != 0 ){
	    		
	    		$tasks = Doctrine_Core::getTable('Console_Task')->getRecurringEvents($this->ID);

	    		// CHECK IF A RECURRING TASK ALREADY EXISTS
	    		if( count( $tasks ) == 0 ){
	    			
	    			// CALCULATE DISPLAY DATE
					$display_date = new Zend_Date();
					
					switch( $this->RECUR_UNIT_TYPE ){
						case "days":
							$display_date->addDay( $this->RECUR_UNITS );
							break;					
						case "months":
							$display_date->addMonth( $this->RECUR_UNITS );
							break;
						case "years":
							$display_date->addYear( $this->RECUR_UNITS );
							break;
					}				
											
					// CREATE RECURRING TASK
					$task = new Console_Task();
					$task->USER_ID = $this->USER_ID;
					$task->PROJECT_ID = $this->PROJECT_ID;
					$task->DESCRIPTION = $this->DESCRIPTION;
					$task->PRIORITY_ID = $this->PRIORITY_ID;
					$task->COMPLETED = null;
					$task->RECUR_UNIT_TYPE = $this->RECUR_UNIT_TYPE;
					$task->RECUR_UNITS = $this->RECUR_UNITS;
					$task->ORIG_ID = $this->ID;
					$task->DISPLAY_DATE = $display_date->toString($format);
					$task->save();
	    			
	    		}	    		
	    		
	    	}
	    	
	    	if($this->PROJECT_ID != '' && $this->PROJECT_ID != null){
	    		
	    		if( $this->Project->AUTO_COMPLETE == 1 ){
	    			
	    			if( $this->Project->getTaskPendingCount() == 0 ){
	    				
	    				$this->Project->markComplete($format);
	    				
	    			}
	    			
	    		}
	    		
	    	}
	    	
    	}
    	 
    }               

    
    public function markIncomplete(){
    	
    	$this->COMPLETED = null;	    	
	    $this->save();
	    
	    $this->deleteRecurringTasks();
    	
    }
    
    
    public function isComplete(){    	
    	
    	if( $this->COMPLETED == null ){
    		return "no";
    	}else{
    		return "yes";
    	}
    	
    }
    
    
    public function deleteRecurringTasks(){
    	
    	$tasks = Doctrine_Core::getTable('Console_Task')->getRecurringEvents($this->ID);
    	
    	foreach( $tasks as $task ){
    		$task->delete();
    	}
    	
    }
    
    
    public function postDelete($event){    	
    	$this->deleteRecurringTasks();   	
    }
    
    
    public function isUserTask($userId){
    	if($userId == $this->USER_ID){
    		return true;
    	}
    	return false;
    }
    
    
	public function addToQueue(){

		$max = 0;
		
   		$tasks = Doctrine_Core::getTable('Console_Task')->getTasksInQueue($this->USER_ID);

   		foreach( $tasks as $task ){
   			   			
   			if( $task->ID == $this->ID ){
   				return false;
   			}
   			
   			if( $task->QUEUE_ORDER > $max ){
   				$max = $task->QUEUE_ORDER;
   			}
   			
   		}
   		
   		$this->QUEUE_ORDER = ( $max + 1 );
   		$this->save();
   		
   		return true;
    	
    }
    
    
    public function removeFromQueue(){
    	
    	$this->QUEUE_ORDER = null;
    	$this->save();
    	
    	return true;
    	
    }
    
    
    public function moveInQueue($dir){
    	
    	if( $dir != 'up' && $dir != 'down' ){
    		throw new Exception('invalid direction when moving task in queue');    		    	
    	}
    	
    	$tasks = Doctrine_Core::getTable('Console_Task')->getTasksInQueue($this->USER_ID);
    	
    	$swapTask = null;
    	
    	foreach( $tasks as $task ){
   			   			
   			if( $dir == 'up' ){
   				
   				if( $task->QUEUE_ORDER < $this->QUEUE_ORDER ){
   					if( $swapTask == null ){
   						$swapTask = $task;
   					}else if( $task->QUEUE_ORDER > $swapTask->QUEUE_ORDER ){
   						$swapTask = $task;
   					}
   				}
   			
   			}else{

   				if( $task->QUEUE_ORDER > $this->QUEUE_ORDER ){
   					if( $swapTask == null ){
   						$swapTask = $task;
   					}else if( $task->QUEUE_ORDER < $swapTask->QUEUE_ORDER ){
   						$swapTask = $task;
   					}
   				}
   				
   			}   			
   				
   		}
   		
   		if( $swapTask != null ){
   			
   			$newOrder = $swapTask->QUEUE_ORDER;
   			$swapTask->QUEUE_ORDER = $this->QUEUE_ORDER;
   			$this->QUEUE_ORDER = $newOrder;
   			$swapTask->save();
   			$this->save();
   			
   		}
    	
    }
    
    
    public function categoryList(){

    	$cats = array();
    	
    	foreach( $this->Categories as $category ){
    		$cats[] = $category->DESCRIPTION;
    	}
    	
    	asort($cats);
    	
    	return implode(', ',$cats);
    	
    }
    
    
    public function hasCategory(Console_Category $category){
    	
    	foreach( $this->Categories as $cat ){
    		if( $category->ID == $cat->ID ){
    			return true;
    		}
    	}
    	
    	return false;
    	
    }
    
    
    public function applyCategory(Console_Category $category){
    	    	    	
    	if( $this->hasCategory($category) === false ){
    		
    		$this->link('Categories', array($category->ID));
    		$this->save();
    		
    	}
    	
    }
    
    
	public function removeCategory(Console_Category $category){
    	    	    	
    	if( $this->hasCategory($category) === true ){
    		
    		$this->unlink('Categories', array($category->ID));
    		$this->save();
    		
    	}
    	
    }
    
    
}