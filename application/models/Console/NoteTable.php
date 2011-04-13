<?php

class Console_NoteTable extends Doctrine_Table{
	
	
	public function getRecentlyViewed( $user_id, $days ){        
       
		$zd = new Zend_Date();	
    	$zd->sub( $days, Zend_Date::DAY );
		
		$query = $this->createQuery('n')        			  
            		  ->where('n.USER_ID = ?', $user_id)
            		  ->andWhere('n.LAST_VIEWED >= ?', $zd->toString('yyyy-MM-dd') )
            		  ->orderBy('n.DESCRIPTION');       
            
       	return $query->execute();
            
    }
	
	
	
}