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
	
	
	public function testIsUserProject(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		$this->assertTrue( $project->isUserProject( $this->testUserId ) );
		
		$project = Doctrine_Core::getTable('Console_Project')->find(3);
		$this->assertFalse( $project->isUserProject( $this->testUserId ) );
		
	}
	
	
	public function testGetStatus(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		$this->assertEquals( $project->getStatus(), 'active' );
		
		$project = Doctrine_Core::getTable('Console_Project')->find(2);
		$this->assertEquals( $project->getStatus(), 'complete' );
		
	}
	
	
	public function testGetTaskPendingCount(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		$this->assertEquals( $project->getTaskPendingCount(), 1 );
		
		$project = Doctrine_Core::getTable('Console_Project')->find(2);
		$this->assertEquals( $project->getTaskPendingCount(), 1 );
		
	}
	

	public function testGetTaskCompleteCount(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		$this->assertEquals( $project->getTaskCompleteCount(), 0 );
		
		$project = Doctrine_Core::getTable('Console_Project')->find(2);
		$this->assertEquals( $project->getTaskCompleteCount(), 1 );		
		
	}
		
	
	public function testGetTaskTotal(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		$this->assertEquals( $project->getTaskTotal(), 1 );
		
		$project = Doctrine_Core::getTable('Console_Project')->find(2);
		$this->assertEquals( $project->getTaskTotal(), 2 );
		
	} 
	
	
	public function testIsComplete(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		$this->assertEquals( $project->isComplete(), false );
		
		$project = Doctrine_Core::getTable('Console_Project')->find(2);
		$this->assertEquals( $project->isComplete(), true );
		
	}
	
	
}

?>