<?php

class Console_ProjectTable extends Doctrine_Table{
	
	public function getByUserId($user_id)
    {        
        return $this->createQuery('p')        		
            		->where('p.USER_ID = ?', $user_id)
            		->orderBy('p.DESCRIPTION')
            		->execute();                                    
    }
	
}