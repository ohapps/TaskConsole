<?php

class Console_ProjectTable extends Doctrine_Table{
	
	public function getByUserId($user_id)
    {        
        return $this->createQuery('p')
        		->leftJoin( 'p.Category c' )
            	->where('c.USER_ID = ?', $user_id)
            	->orderBy('c.DESCRIPTION, p.DESCRIPTION')
            	->execute();                                    
    }
	
}