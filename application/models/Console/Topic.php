<?php

class Console_Topic extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('topics');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));
    	$this->hasColumn('USER_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 4,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));	
		$this->hasColumn('DESCRIPTION', 'string', null, array(
             'type' => 'string',
			 'length' => 100,
             'fixed' => false,
             'primary' => false,
             'notnull' => false,
             'autoincrement' => false,
             ));        
	}

	
	public function setUp(){
    	$this->hasMany('Console_Note as Notes', array(
                'local' => 'ID',
                'foreign' => 'TOPIC_ID'
            )
        );
    }
	
    
    public function isUserTopic($user_id){
    	if( $user_id == $this->USER_ID ){
    		return true;
    	}else{
    		return false;
    	}
    }
    
    
}