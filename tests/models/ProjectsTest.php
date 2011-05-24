<?php

require_once 'AbstractTest.php';

class ProjectsTest extends AbstractTest {
	
	
	public function testGetByUserId(){
				
		$cnt = count( Doctrine_Core::getTable('Console_Project')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 2, $cnt );								
		
	}			
	
	
	public function testGetPagedResultsByUserId(){
		
		$pager = Doctrine_Core::getTable('Console_Project')->getPagedResultsByUserId(
			$this->testUserId,
			array(				
				"sort" => 'DESCRIPTION',
				"dir" => 'ASC',
				"status" => 'active',
				"category" => ''
			),
			1,
			50			
		);
		
		$projects = $pager->execute();
		
		$this->assertEquals( 1, count( $projects ) );
		
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
	
	
	public function testMarkComplete(){
		
		$config = $this->bootstrap->getOption('app');
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		
		$this->assertEquals( $project->isComplete(), false );
		
		$project->markComplete($config['date']['dbFormat']);
		
		$this->assertEquals( $project->isComplete(), true );
		
	}
	
	
	public function testMarkIncomplete(){				
		
		$project = Doctrine_Core::getTable('Console_Project')->find(2);
		
		$this->assertEquals( $project->isComplete(), true );
		
		$project->markIncomplete();
		
		$this->assertEquals( $project->isComplete(), false );	
		
	}
	
	
	public function testHasCategory(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		
		$category = Doctrine_Core::getTable('Console_Category')->find(1);
		
		$this->assertEquals( $project->hasCategory($category), true );
				
	}
	
	
	public function testSetCategoriesFromArray(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		
		$category2 = Doctrine_Core::getTable('Console_Category')->find(2);
		
		$category3 = Doctrine_Core::getTable('Console_Category')->find(3);
		
		$this->assertEquals( $project->hasCategory($category2), false );
		
		$this->assertEquals( $project->hasCategory($category2), false );
		
		$project->setCategoriesFromArray(array(2,3));
		
		$this->assertEquals( $project->hasCategory($category2), true );
		
		$this->assertEquals( $project->hasCategory($category2), true );
		
	}
	
	
	public function testApplyCategory(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		
		$category = Doctrine_Core::getTable('Console_Category')->find(2);
		
		$this->assertEquals( $project->hasCategory($category), false );
		
		$project->applyCategory($category);
		
		$this->assertEquals( $project->hasCategory($category), true );
		
	}
	
	
	public function testRemoveCategory(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		
		$category = Doctrine_Core::getTable('Console_Category')->find(1);
		
		$this->assertEquals( $project->hasCategory($category), true );
		
		$project->removeCategory($category);
		
		$this->assertEquals( $project->hasCategory($category), false );
		
	}
	
	
	public function testCategoryList(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		
		$this->assertEquals( $project->categoryList(), 'Work' );
		
		$category = Doctrine_Core::getTable('Console_Category')->find(2);
		
		$project->applyCategory($category);
		
		$this->assertEquals( $project->categoryList(), 'Personal, Work' );
		
	}
	
	
	public function testCategoryIdsList(){
		
		$project = Doctrine_Core::getTable('Console_Project')->find(1);
		
		$this->assertEquals( $project->categoryIdsList(), '1' );
		
		$category = Doctrine_Core::getTable('Console_Category')->find(2);
		
		$project->applyCategory($category);
		
		$this->assertEquals( $project->categoryIdsList(), '1,2' );
		
	}
	
	
}

?>