<?php

class Console_TaskCategory extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('task_categories');
		$this->hasColumn('ID', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));    	             	
        $this->hasColumn('CATEGORY_ID', 'integer', 11, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
       	$this->hasColumn('TASK_ID', 'integer', 11, array(
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