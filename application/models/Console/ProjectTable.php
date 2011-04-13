<?php

class Console_ProjectTable extends Doctrine_Table{
	
	
	public function getByUserId($user_id,$status=null){
		        
        $query = $this->createQuery('p')        		
            		->where('p.USER_ID = ?', $user_id)
            		->orderBy('p.DESCRIPTION');

        if( $status == 'active'){    		    	
			$query->andWhere("p.COMPLETED is null");						
        }else if( $status == 'complete' ){ 
			$query->andWhere("p.COMPLETED is not null");
        }
		
        return $query->execute();
            		
    }
    
    
	public function getPagedResultsByUserId($user_id, $options = array(), $page=1, $pageSize=50){        
        
    	$query = $this->createQuery('p')
    				  ->leftJoin( 'p.Categories c' )        		
            		  ->where('p.USER_ID = ?', $user_id)
            		  ->orderBy('p.DESCRIPTION');        						
		
		if(isset($options['status'])){
			switch($options['status']){
				case "active":
					$query->andWhere("p.COMPLETED is null");
					break;				
				case "complete":
					$query->andWhere("p.COMPLETED is not null");
					break;				
			}
		}    

		if(isset($options['category'])){
			if($options['category'] != ''){
				$query->andWhere("c.ID = ?",$options['category']);
			}
		}
            		
		if( isset($options['sort']) && isset($options['dir']) ){			
			$query->orderBy( $options['sort'] . ' ' . $options['dir'] );
		}		
		
        $pager = new Doctrine_Pager(
         	$query,
            $page,
            $pageSize
        );

        return $pager;            	       
            	
    }
	
    
}