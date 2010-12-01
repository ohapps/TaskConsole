<?php

class TestConfiguration {		
	
	
	static function setupUser(){
		
		$user = Zend_Registry::get('user');
		$user->auth( 'demo', 'password' );
		
	}
	
	
	static function setupDatabase(){
						
		Doctrine_Core::loadData( dirname(__FILE__) . '/data' );		
		
	}
	
	
}

?>