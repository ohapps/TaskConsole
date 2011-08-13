<?php

require_once 'AbstractControllerTest.php';

class UserControllerTest extends AbstractControllerTest {
	
	
	public function testLoginAction(){
        
		$this->dispatch('/user/login');
        $this->assertController('user');
        $this->assertAction('login');                       
                
    }
    
    
	public function testLogoutAction(){
        
		$this->dispatch('/user/logout');
        $this->assertController('user');
        $this->assertAction('logout');                       
                
    }
    
    
	public function testAuthAction(){
        
		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'un' => 'demo',
				'ps' => 'demo'				
			)
		);
		$this->dispatch('/user/auth');
        $this->assertController('user');
        $this->assertAction('auth');
        $this->assertContains('"success":true', $this->getResponse()->getBody());                       
                
    }
    
    
	public function testStatusAction(){
        
		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array());
		$this->dispatch('/user/status');
        $this->assertController('user');
        $this->assertAction('status');
        $this->assertContains('"success":true', $this->getResponse()->getBody());                       
                
    }
	
	
}