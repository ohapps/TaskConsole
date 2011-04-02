<?php

class Console_Note extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('notes');
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
    	$this->hasColumn('TOPIC_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));	
		$this->hasColumn('CONTENTS', 'string', null, array(
             'type' => 'string',
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
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
     	$this->hasColumn('LAST_UPDATE', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
        $this->hasColumn('LAST_VIEWED', 'timestamp', null, array(
             'type' => 'timestamp',
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));
	}

	
	public function setUp(){		
    	$this->hasOne('Console_Topic as Topic', array(
                'local' => 'TOPIC_ID',
                'foreign' => 'ID'
            )
        );
    }
    
    
    public function markViewed($date){    	
    	$this->LAST_VIEWED = $date;
    	$this->save();
    }
	
    
}