<?php

require_once 'AbstractTest.php';

class ProjectCategoriesTest extends AbstractTest {
	
	
	public function testGetByUserId(){
				
		$cnt = count( Doctrine_Core::getTable('Console_ProjectCategory')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 3, $cnt );								
		
	}
	
	
	public function testIsUserProjectCategory(){
		
		$category = Doctrine_Core::getTable('Console_ProjectCategory')->find(1);
		$this->assertTrue( $category->isUserProjectCategory( $this->testUserId ) );		
		
		$category = Doctrine_Core::getTable('Console_ProjectCategory')->find(4);
		$this->assertFalse( $category->isUserProjectCategory( $this->testUserId ) );
		
	}
	
	
}	

?>