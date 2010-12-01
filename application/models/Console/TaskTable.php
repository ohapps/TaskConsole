<?php

class Console_TaskTable extends Doctrine_Table{
	
	
	public function getByUserId($user_id, $options = array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1 ))
    {        
        $query = $this->createQuery('t')
        		->leftJoin( 't.Project p' )
        		->leftJoin( 'p.Category c' )
            	->where('c.USER_ID = ?', $user_id);

    	if(isset($options['category'])){			
			if( $options['category'] != "" ){
				$query->andWhere("c.ID = ?", $options['category']);										    		
	    	}
		}
    	
		if(isset($options['project'])){
	    	if( $options['project'] != "" ){	    		
	    		$query->andWhere("p.ID = ?", $options['project']);    		
	    	}
		}
            	
		if(isset($options['complete'])){
	    	if( $options['complete'] == 0 ){	    		
	    		$query->andWhere("t.COMPLETE = ?", 0);	    	
	    	}
		}		
    	
		if(isset($options['disp_high'])){
	    	if( $options['disp_high'] == 0 ){	    		
	    		$query->andWhere("t.PRIORITY_ID != ?", 1);    		
	    	}
		}
		
		if(isset($options['disp_normal'])){
	    	if( $options['disp_normal'] == 0 ){
	    		$query->andWhere("t.PRIORITY_ID != ?", 2);	    		    		    		
	    	}
		}
    	
		if(isset($options['disp_low'])){
	    	if( $options['disp_low'] == 0 ){
	    		$query->andWhere("t.PRIORITY_ID != ?", 3);	    		    		    		
	    	}
		}    	    	    					

		if(!isset($options['disp_pending'])){			
			$query->andWhere('( t.DISPLAY_DATE <= CURDATE() or t.DISPLAY_DATE is null )');
		}else if( $options['disp_pending'] != 1 ){							
			$query->andWhere('( t.DISPLAY_DATE <= CURDATE() or t.DISPLAY_DATE is null )');
		}            	

		if(isset($options['days_til_due'])){
			$zd = new Zend_Date();	
    		$zd->add( $options['days_til_due'], Zend_Date::DAY );
    		$query->andWhere('t.DUE_DATE <= ?', $zd->toString('yyyy-MM-dd') );
		}
		
		$query->orderBy('t.PRIORITY_ID, c.DESCRIPTION, p.DESCRIPTION');
		
    	return $query->execute();
            	
    }
    
    
    public function getRecurringEvents($id){
    	
    	return 	$this->createQuery('t')
    			->where('t.ORIG_ID = ?', $id)
    			->andWhere('t.COMPLETE = ?', 0)
    			->execute();
    	
    }
	
	
}