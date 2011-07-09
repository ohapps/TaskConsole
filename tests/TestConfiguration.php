<?php

class TestConfiguration {					
	
	
	static function setupDatabase(){
						
		Doctrine_Core::loadData( dirname(__FILE__) . '/data' );		
		
	}
	
	
}

?>