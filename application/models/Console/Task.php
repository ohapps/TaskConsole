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
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
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
             'unsigned' => 1,
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
	
    
    public function markComplete($format){
    	
    	if( $this->COMPLETE == 0 ){
    		
    		$today = new Zend_Date();
    		
	    	$this->COMPLETE = 1;
	    	$this->COMPLETE_DATE = $today->toString($format);
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
					$task->PROJ_ID = $this->PROJ_ID;
					$task->DESCRIPTION = $this->DESCRIPTION;
					$task->PRIORITY_ID = $this->PRIORITY_ID;
					$task->COMPLETE = 0;
					$task->RECUR_UNIT_TYPE = $this->RECUR_UNIT_TYPE;
					$task->RECUR_UNITS = $this->RECUR_UNITS;
					$task->ORIG_ID = $this->ID;
					$task->DISPLAY_DATE = $display_date->toString($format);
					$task->save();
	    			
	    		}	    		
	    		
	    	}
	    	
    	}
    	 
    }
        
    
    public function isComplete(){
    	return strtr($this->COMPLETE,array( "1"=>"yes", "0"=>"no" ));
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
    
    
}