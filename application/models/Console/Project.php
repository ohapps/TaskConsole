<?php

class Console_Project extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('projects');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));
    	$this->hasColumn('CATEGORY', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
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
     	$this->hasColumn('COMPLETE', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
	}		
	
	
	public function setUp(){
    	$this->hasOne('Console_ProjectCategory as Category', array(
                'local' => 'CATEGORY',
                'foreign' => 'ID'
            )
        );
        $this->hasMany('Console_Task as Tasks', array(
                'local' => 'ID',
                'foreign' => 'PROJ_ID'
            )
        );
    }
	
    
	public function fullDesc(){
		return $this->Category->DESCRIPTION . ' - ' . $this->DESCRIPTION;
	}
    
    
    public function isActive(){
    	if( $this->COMPLETE == 0 ){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    
}