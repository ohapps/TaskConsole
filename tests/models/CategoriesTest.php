<?php

require_once 'AbstractTest.php';

class CategoriesTest extends AbstractTest {
	
	
	public function testGetByUserId(){
				
		$cnt = count( Doctrine_Core::getTable('Console_Category')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 4, $cnt );								
		
	}
	
	
	public function testIsUserCategory(){
		
		$category = Doctrine_Core::getTable('Console_Category')->find(1);
		$this->assertTrue( $category->isUserCategory( $this->testUserId ) );		
		
		$category = Doctrine_Core::getTable('Console_Category')->find(5);
		$this->assertFalse( $category->isUserCategory( $this->testUserId ) );
		
	}
	
	
}	

?>