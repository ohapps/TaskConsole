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
	}		
	
	
	public function setUp(){
    	$this->hasMany('Console_Category as Categories', array(
                'local' => 'PROJECT_ID',
                'foreign' => 'CATEGORY_ID',
                'refClass' => 'Console_ProjectCategory'
            )
        );
        $this->hasMany('Console_Note as Notes', array(
                'local' => 'PROJECT_ID',
                'foreign' => 'NOTE_ID',
                'refClass' => 'Console_ProjectNote'
            )
        );
        $this->hasMany('Console_Task as Tasks', array(
                'local' => 'ID',
                'foreign' => 'PROJECT_ID'
            )
        );
    }
	
    
	public function fullDesc(){
		return $this->Category->DESCRIPTION . ' - ' . $this->DESCRIPTION;
	}
    
    
    public function isActive(){
    	if( $this->COMPLETED == null ){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    
}