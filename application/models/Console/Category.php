<?php

class Console_Category extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('categories');
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
        	 'length' => 45,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));     	
        
	}

	
	public function setUp(){		
		$this->hasMany('Console_Project as Projects', array(
                'local' => 'CATEGORY_ID',
                'foreign' => 'PROJECT_ID',
                'refClass' => 'Console_ProjectCategory'
            )
        );
        $this->hasMany('Console_Task as Tasks', array(
                'local' => 'CATEGORY_ID',
                'foreign' => 'TASK_ID',
                'refClass' => 'Console_TaskCategory'
            )
        );
    }
	
    
    public function isUserCategory($user_id){
    	if( $this->USER_ID == $user_id ){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    
}