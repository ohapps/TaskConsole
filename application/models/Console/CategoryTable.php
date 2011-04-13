<?php

class Console_CategoryTable extends Doctrine_Table{
	
	public function getByUserId($user_id)
    {        
        return $this->createQuery('c')        		
            		->where('c.USER_ID = ?', $user_id)
            		->orderBy('c.DESCRIPTION')
            		->execute();                                    
    }
	
}