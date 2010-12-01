<?php

require_once 'AbstractTest.php';

class NotesTest extends AbstractTest {			
	
	
	public function testGetRecentlyViewedNotes(){
				
		$cnt = count( Doctrine_Core::getTable('Console_Note')->getRecentlyViewed( $this->testUserId, 7 ) );
		$this->assertSame( 1, $cnt );								
		
	}
	
	
	public function testMarkViewed(){
		
		$config = $this->bootstrap->getOption('app');
		$note = Doctrine_Core::getTable('Console_Note')->find(1);
				
		$today = new Zend_Date();
		$note->markViewed($today->toString($config['date_format']));

		$this->assertEquals( $today->toString($config['date_format']), $note->LAST_VIEWED );
		
	}
	
	
}	

?>