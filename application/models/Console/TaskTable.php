<?php

class Console_TaskTable extends Doctrine_Table{
	
	
	public function getByUserId($user_id, $options = array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1 )){        
        
    	$query = $this->createQuery('t')
        		->leftJoin( 't.Project p' )        		
            	->where('t.USER_ID = ?', $user_id);
       		
    	return $query->execute();
            	
    }
    
    
	public function getPagedResultsByUserId($user_id, $options = array(), $page=1, $pageSize=50){        
        
    	$query = $this->createQuery('t')
        		->leftJoin( 't.Project p' )        		
        		->leftJoin( 't.Categories c' )
            	->where('t.USER_ID = ?', $user_id);        
		
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

		if(isset($options['status'])){
			switch($options['status']){
				case "queue":
					$query->andWhere("t.COMPLETED is null")
						  ->andWhere("t.QUEUE_ORDER is not null")
						  ->andWhere('( t.DISPLAY_DATE <= CURDATE() or t.DISPLAY_DATE is null )');
					break;
				case "pending":
					$query->andWhere("t.COMPLETED is null")
						  ->andWhere('( t.DISPLAY_DATE <= CURDATE() or t.DISPLAY_DATE is null )');
					break;
				case "complete":
					$query->andWhere("t.COMPLETED is not null");
					break;
				case "upcoming":
					$query->andWhere("t.COMPLETED is null")
						  ->andWhere('t.DISPLAY_DATE > CURDATE()');
					break; 				
				case "search":
					if(isset($options['keyword'])){											
						$query->andWhere( "lower(t.description) like ?", '%' . strtolower($options['keyword']) . '%' );						
					}
					break;
			}
		}

		if(isset($options['priorities'])){
			if(is_array($options['priorities'])){
				if(count($options['priorities']) > 0){
					$query->andWhereIn("t.PRIORITY_ID",$options['priorities']);
				}
			}
		}
		
		if( isset($options['sort']) && isset($options['dir']) ){
			$sort = strtr( 
				$options['sort'], 
				array(
					"PRIORITY" => "t.PRIORITY_ID",
					"PROJECT" => "p.DESCRIPTION"				
				)
			);
			$query->orderBy( $sort . ' ' . $options['dir'] );
		}
		
        $pager = new Doctrine_Pager(
         	$query,
            $page,
            $pageSize
        );

        return $pager;            	       
            	
    }
    
    
    public function getRecurringEvents($id){
    	
    	return 	$this->createQuery('t')
    			->where('t.ORIG_ID = ?', $id)
    			->andWhere('t.COMPLETED is null')
    			->execute();
    	
    }
	
    
	public function getTasksInQueue($userId){
    	
    	return 	$this->createQuery('t')
    			->where('t.USER_ID = ?', $userId)
    			->andWhere('t.COMPLETED is null')
    			->andWhere('t.QUEUE_ORDER is not null')
    			->execute();
    	
    }
    
	
}