<?php

require_once 'AbstractTest.php';

class ProjectsTest extends AbstractTest {
	
	
	public function testGetByUserId(){
				
		$cnt = count( Doctrine_Core::getTable('Console_Project')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 2, $cnt );								
		
	}			

	
	public function testIsActive(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		$this->assertTrue( $project->isActive() );
		
		$project = Doctrine_Core::getTable('Console_Project')->find(2);
		$this->assertFalse( $project->isActive() );
		
	}
		
}

?>