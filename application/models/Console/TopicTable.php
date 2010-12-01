<?php

class Console_TopicTable extends Doctrine_Table{
	
	
	public function getByUserId( $user_id, $search = null )
    {        
       $query = $this->createQuery('t')
        	->leftJoin( 't.Notes n' )
            ->where('t.USER_ID = ?', $user_id)
            ->orderBy('t.DESCRIPTION, n.DESCRIPTION');

       if( $search != null ){
       		$search = "%". $search ."%";
       		$query->andWhere('( lower(t.DESCRIPTION) like lower(?) or lower(CONTENTS) like lower(?) )',array($search,$search) );       		
       }
            
       return $query->execute();
            
    }
    
	
}