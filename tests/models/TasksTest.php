<?php

require_once 'AbstractTest.php';

class TasksTest extends AbstractTest {
	
	
	public function testGetAllUserTasks(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 4, $cnt );									
		
	}
	
	
	public function testGetPagedResultsByUserId(){
		
		$options = array();
		
		$pager = Doctrine_Core::getTable('Console_Task')->getPagedResultsByUserId($this->testUserId,$options, 1, 2);
		
		$this->assertEquals( 2, count( $pager->execute() ) );					
		$this->assertEquals( 4, $pager->getNumResults() );        

		$options = array(
			"category" => 1,
			"project" => 1,
			"sort" => 'QUEUE_ORDER',
			"dir" => 'ASC',
			"status" => 'queue',
			"priorities" => array(1,2,3)
		);
		
		$pager = Doctrine_Core::getTable('Console_Task')->getPagedResultsByUserId($this->testUserId,$options, 1, 2);
		
		$this->assertEquals( 1, count( $pager->execute() ) );					
		$this->assertEquals( 1, $pager->getNumResults() );		
		
	}
	
		
	public function testGetRecurringEvents(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getRecurringEvents(1) );
		$this->assertEquals( 1, $cnt );
		
	}
	
	
	public function testGetTasksInQueue(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getTasksInQueue( $this->testUserId ) );
		$this->assertEquals( 1, $cnt );
		
	}	
	
	
	public function testMarkComplete(){
		
		$config = $this->bootstrap->getOption('app');
						
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		
		$this->assertEquals( $task->isComplete(), 'no' );
		
		$task->markComplete($config['date']['dbFormat']);	

		$this->assertEquals( $task->isComplete(), 'yes' );
		
	}	
	
	
	public function testMarkCompleteWithRecur(){
		
		$config = $this->bootstrap->getOption('app');

		$cnt = count( Doctrine_Core::getTable('Console_Task')->getRecurringEvents(2) );
		$this->assertEquals( 0, $cnt );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(2);
		
		$this->assertEquals( $task->isComplete(), 'no' );
		
		$task->markComplete($config['date']['dbFormat']);	

		$this->assertEquals( $task->isComplete(), 'yes' );
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getRecurringEvents(2) );
		$this->assertEquals( 1, $cnt );		
		
	}
	
		
	public function testDeleteWithRecur(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 4, $cnt );	
		
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		$task->delete();
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 2, $cnt );
		
	}
	
	
	public function testMarkIncomplete(){
		
		$task = Doctrine_Core::getTable('Console_Task')->find(3);
		
		$this->assertEquals( $task->isComplete(), 'yes' );	
		
		$task->markIncomplete();
		
		$this->assertEquals( $task->isComplete(), 'no' );
		
	} 
	

	public function testIsUserTask(){
		
		$task = Doctrine_Core::getTable('Console_Task')->find(5);
		
		$this->assertEquals( $task->isUserTask( $this->testUserId ), false );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		
		$this->assertEquals( $task->isUserTask( $this->testUserId ), true );
		
	}
	
	
	public function testIsQueued(){
		
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		
		$this->assertEquals( $task->isQueued(), true );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(2);
		
		$this->assertEquals( $task->isQueued(), false );
		
	}
	
	
	public function testAddToQueue(){
		
		$task = Doctrine_Core::getTable('Console_Task')->find(2);
		
		$this->assertEquals( $task->isQueued(), false );
		
		$task->addToQueue();
		
		$this->assertEquals( $task->isQueued(), true );
		
	}
	
	
	public function testRemoveFromQueue(){
		
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		
		$this->assertEquals( $task->isQueued(), true );
		
		$task->removeFromQueue();
		
		$this->assertEquals( $task->isQueued(), false );
		
	}
	
	
	public function testCategoryList(){
		
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		
		$this->assertEquals( $task->categoryList(), 'Work' );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(2);
		
		$this->assertEquals( $task->categoryList(), 'Personal, Work' );
		
	}
	
	
	
}

?>