<?php

class TestConfiguration {		
	
	
	static function setupUser(){
		
		$userManager = Zend_Registry::get('userManager');
		$userManager->login( 'demo' );
		
	}
	
	
	static function setupDatabase(){
						
		Doctrine_Core::loadData( dirname(__FILE__) . '/data' );		
		
	}
	
	
}

?>