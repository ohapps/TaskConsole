<?php

class Console_Project extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('projects');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));
        $this->hasColumn('USER_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));           	
        $this->hasColumn('DESCRIPTION', 'string', null, array(
             'type' => 'string',
        	 'length' => 200,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('COMMENTS', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
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
        $this->hasColumn('AUTO_COMPLETE', 'integer', 1, array(
             'type' => 'integer',
             'length' => 1,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             )); 
	}		
	
	
	public function setUp(){
    	$this->hasMany('Console_Category as Categories', array(
                'local' => 'PROJECT_ID',
                'foreign' => 'CATEGORY_ID',
                'refClass' => 'Console_ProjectCategory'
            )
        );       
        $this->hasMany('Console_Task as Tasks', array(
                'local' => 'ID',
                'foreign' => 'PROJECT_ID'
            )
        );
    }
    
    
    public function preDelete($event){
    	foreach( $this->Tasks as $task ){
    		$task->PROJECT_ID = null;
    		$task->save();
    	}	
    }	    	
    
    
    public function isActive(){
    	if( $this->COMPLETED == null ){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    
    public function isUserProject($userId){
    	if( $this->USER_ID == $userId ){
    		return true;
    	}
    	return false;
    }
    
    
    public function getStatus(){
    	if($this->isActive() === true){
    		return 'active';
    	}else{
    		return 'complete';
    	}
    }
    
    
    public function getTaskPendingCount(){
    	
    	$cnt = 0;
    	
    	foreach($this->Tasks as $task){
    		if( $task->isComplete() == 'no' ){
				$cnt++;    			
    		}
    	} 
    	
    	return $cnt;
    	
    }
    
    
	public function getTaskCompleteCount(){
    	
    	$cnt = 0;
    	
    	foreach($this->Tasks as $task){
    		if( $task->isComplete() == 'yes' ){
				$cnt++;    			
    		}
    	} 
    	
    	return $cnt;
    	
    }
    
    
    public function getTaskTotal(){
    	return count( $this->Tasks );	
    }
    
    
    public function isComplete(){
    	
    	if( $this->COMPLETED == null || $this->COMPLETED == '' ){
    		return false;
    	}
    	
    	return true;
    	
    }
    
    
    public function markComplete($format){
    	
    	if( $this->isComplete() === false ){
    		
    		$today = new Zend_Date();
    			    	
	    	$this->COMPLETED = $today->toString($format);	    	
	    	$this->save();	   
	    	
	    	foreach($this->Tasks as $task){
    			if( $task->isComplete() == 'no' ){
					$task->markComplete($format);    			
    			}
    		} 
	    	
    	}
    	 
    }           
    
    
    public function markIncomplete(){
    	
    	if( $this->isComplete() === true ){
    		
    		$this->COMPLETED = null;
    		$this->save();
    		
    	}
    	
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
    
    
	public function categoryList(){

    	$cats = array();
    	
    	foreach( $this->Categories as $category ){
    		$cats[] = $category->DESCRIPTION;
    	}
    	
    	asort($cats);
    	
    	return implode(', ',$cats);
    	
    }
    
    
	public function categoryIdsList(){

    	$cats = array();
    	
    	foreach( $this->Categories as $category ){
    		$cats[] = $category->ID;
    	}
    	
    	asort($cats);
    	
    	return implode(',',$cats);
    	
    }
    
    
}