<?php

class Console_ProjectCategory extends Doctrine_Record {
	
	
	public function setTableDefinition(){
		
		$this->setTableName('project_categories');
		$this->hasColumn('ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => true,
             'autoincrement' => true,
             ));    	             	
        $this->hasColumn('CATEGORY_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
       	$this->hasColumn('PROJECT_ID', 'integer', 4, array(
             'type' => 'integer',
             'length' => 11,
             'unsigned' => 1,
             'primary' => false,
             'notnull' => true,
             'autoincrement' => false,
             ));
	}

	
	public function setUp(){    	
        $this->hasOne('Console_Project as Project', array(
                'local' => 'PROJECT_ID',
                'foreign' => 'ID'
            )
        );
        $this->hasOne('Console_Category as Category', array(
                'local' => 'CATEGORY_ID',
                'foreign' => 'ID'
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