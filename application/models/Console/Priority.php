<?php

class Console_Priority extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('priorities');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));    	
        $this->hasColumn('DESCRIPTION', 'string', null, array(
             'type' => 'string',
        	 'length' => 25,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));     	
	}

	
	public function setUp(){
    	 $this->hasMany('Console_Task as Tasks', array(
                'local' => 'ID',
                'foreign' => 'PRIORITY_ID'
            )
        );
    }
	
    
}