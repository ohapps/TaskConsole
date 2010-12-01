<?php

require_once 'AbstractControllerTest.php';

class IndexControllerTest extends AbstractControllerTest {						
	
	
	public function testIndexAction(){
        
		$this->dispatch('/index');
        $this->assertController('index');
        $this->assertAction('index');
                
    }   
    
    
}