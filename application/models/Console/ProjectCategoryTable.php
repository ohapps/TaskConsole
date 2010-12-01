<?php

class Console_ProjectCategoryTable extends Doctrine_Table{
	
	public function getByUserId($user_id)
    {        
        return $this->createQuery('c')
        	->leftJoin( 'c.Projects p' )
            ->where('c.USER_ID = ?', $user_id)
            ->orderBy('c.DESCRIPTION, p.DESCRIPTION')
            ->execute();
    }
	
}