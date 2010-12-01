<?php

class Console_ProjectCategory extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('project_categories');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));    	
        $this->hasColumn('DESCRIPTION', 'string', null, array(
             'type' => 'string',
        	 'length' => 45,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));     	
        $this->hasColumn('USER_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
	}

	
	public function setUp(){
    	$this->hasMany('Console_Project as Projects', array(
                'local' => 'ID',
                'foreign' => 'CATEGORY'
            )
        );	
    }
	
    
    public function isUserProjectCategory($user_id){
    	if( $this->USER_ID == $user_id ){
    		return true;
    	}else{
    		return false;
    	}
    }
    
}