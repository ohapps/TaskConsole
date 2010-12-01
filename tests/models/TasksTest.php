<?php

require_once 'AbstractTest.php';

class TasksTest extends AbstractTest {
	
	
	public function testGetAllUserTasks(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 3, $cnt );	
						
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 0, 'disp_low' => 0 ) ) );
		$this->assertEquals( 1, $cnt );
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 0, 'disp_normal' => 1, 'disp_low' => 0 ) ) );
		$this->assertEquals( 1, $cnt );
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 0, 'disp_normal' => 0, 'disp_low' => 1 ) ) );
		$this->assertEquals( 1, $cnt );
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0 ) ) );
		$this->assertEquals( 2, $cnt );
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0, 'category' => 1 ) ) );
		$this->assertEquals( 1, $cnt );
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0, 'project' => 1 ) ) );
		$this->assertEquals( 1, $cnt );
		
	}
	
	
	public function testGetRecurringEvents(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getRecurringEvents(1) );
		$this->assertEquals( 1, $cnt );
		
	}
	
	
	public function testMarkComplete(){
		
		$config = $this->bootstrap->getOption('app');
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0 ) ) );
		$this->assertEquals( 2, $cnt );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		$task->markComplete($config['date_format']);
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0 ) ) );
		$this->assertEquals( 1, $cnt );				
		
	}
	
	
	public function testMarkCompleteWithRecur(){
		
		$config = $this->bootstrap->getOption('app');
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0 ) ) );
		$this->assertEquals( 2, $cnt );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(2);
		$task->markComplete($config['date_format']);
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0, 'disp_pending' => 1 ) ) );
		$this->assertEquals( 2, $cnt );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(2);
		$this->assertEquals( 'yes', $task->isComplete() );						
		
	}
	
	
	public function testDeleteWithRecur(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0 ) ) );
		$this->assertEquals( 2, $cnt );
		
		$task = Doctrine_Core::getTable('Console_Task')->find(1);
		$task->delete();
		
		$cnt = count( Doctrine_Core::getTable('Console_Task')->getByUserId( $this->testUserId, array( 'disp_high' => 1, 'disp_normal' => 1, 'disp_low' => 1, 'complete' => 0 ) ) );
		$this->assertEquals( 0, $cnt );
		
	}
	
		
}

?>