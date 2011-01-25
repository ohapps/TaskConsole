<?php

class Console_ProjectNote extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('project_notes');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));    	             	
        $this->hasColumn('PROJECT_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
       	$this->hasColumn('NOTE_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
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
	        
    
}