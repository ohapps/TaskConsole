<?php

require_once 'AbstractControllerTest.php';

class IndexControllerTest extends AbstractControllerTest {						
	
	
	public function testIndexAction(){
        
		$this->login();
		$this->dispatch('/index');
        $this->assertController('index');
        $this->assertAction('index');
                
    }   
    
    
}