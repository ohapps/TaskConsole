<?php

require_once 'AbstractTest.php';

class TopicsTest extends AbstractTest {			
	
	
	public function testGetUserTopics(){
		
		$cnt = count( Doctrine_Core::getTable('Console_Topic')->getByUserId( $this->testUserId ) );
		$this->assertEquals( 3, $cnt );
		
		$cnt = count( Doctrine_Core::getTable('Console_Topic')->getByUserId( $this->testUserId, 'mysql' ) );
		$this->assertEquals( 1, $cnt );
		
	}			
	
	
	public function testIsUserTopic(){
		
		$topic = Doctrine_Core::getTable('Console_Topic')->find(1);
		$this->assertTrue( $topic->isUserTopic($this->testUserId) );
		
		$topic = Doctrine_Core::getTable('Console_Topic')->find(4);
		$this->assertFalse( $topic->isUserTopic($this->testUserId) );
		
	}
	
	
}	
	
?>