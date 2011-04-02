<?php

class Console_User extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('users');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));        
        $this->hasColumn('FIRST_NAME', 'string', null, array(
             'type' => 'string',
        	 'length' => 45,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('LAST_NAME', 'string', null, array(
             'type' => 'string',
        	 'length' => 45,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('USERNAME', 'string', null, array(
             'type' => 'string',
        	 'length' => 45,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('PASSWORD', 'string', null, array(
             'type' => 'string',
        	 'length' => 45,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('ACTIVE', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));	     
     	$this->hasColumn('CREATED', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));        
	}		           
	
    
}