<?php

require_once 'AbstractControllerTest.php';

class NotesControllerTest extends AbstractControllerTest {
	
	
	public function testIndexAction(){
        
		$this->dispatch('/notes');
        $this->assertController('notes');
        $this->assertAction('index');
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array('id' => '1'));
		$this->dispatch('/notes');
        $this->assertController('notes');
        $this->assertAction('index');       
                
    }
    
    
	public function testViewAction(){        		

		$this->request->setMethod('POST')->setPost(array('id' => '1'));
		$this->dispatch('/notes/view');
        $this->assertController('notes');
        $this->assertAction('view');       		
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array('id' => '4'));
		$this->dispatch('/notes/view');
        $this->assertController('error');
        $this->assertAction('error');                                        
                
    }
    
    
    public function testViewWithInvalidIdAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array('id' => '0'));
		$this->dispatch('/notes/view');
        $this->assertController('error');
        $this->assertAction('error');
    	
    }	
	
    
    public function testSaveTopicAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1',
				'DESCRIPTION' => 'test'				
			)
		);
		$this->dispatch('/notes/save-topic');
        $this->assertController('notes');
        $this->assertAction('save-topic');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'test'				
			)
		);
		$this->dispatch('/notes/save-topic');
        $this->assertController('notes');
        $this->assertAction('save-topic');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '0',
				'DESCRIPTION' => 'test'				
			)
		);
		$this->dispatch('/notes/save-topic');
        $this->assertController('notes');
        $this->assertAction('save-topic');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '4',
				'DESCRIPTION' => 'test'				
			)
		);
		$this->dispatch('/notes/save-topic');
        $this->assertController('notes');
        $this->assertAction('save-topic');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
    }
    
    
    public function testDeleteTopicAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1'				
			)
		);
		$this->dispatch('/notes/delete-topic');
        $this->assertController('notes');
        $this->assertAction('delete-topic');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '0'				
			)
		);
		$this->dispatch('/notes/delete-topic');
        $this->assertController('notes');
        $this->assertAction('delete-topic');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '4'				
			)
		);
		$this->dispatch('/notes/delete-topic');
        $this->assertController('notes');
        $this->assertAction('delete-topic');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array()
		);
		$this->dispatch('/notes/delete-topic');
        $this->assertController('notes');
        $this->assertAction('delete-topic');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
    }
    
    
    public function testSaveNoteAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'DATA' =>
				array(
					'ID' => '1',
					'TOPIC_ID' => '1',
					'CONTENTS' => 'test',
					'DESCRIPTION' => 'test'				
				)
			)
		);
		$this->dispatch('/notes/save-note');
        $this->assertController('notes');
        $this->assertAction('save-note');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'DATA' =>
				array(
					'ID' => '',
					'TOPIC_ID' => '1',
					'CONTENTS' => 'test',
					'DESCRIPTION' => 'test'				
				)
			)
		);
		$this->dispatch('/notes/save-note');
        $this->assertController('notes');
        $this->assertAction('save-note');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'DATA' =>
				array(
					'ID' => '1',
					'TOPIC_ID' => '4',
					'CONTENTS' => 'test',
					'DESCRIPTION' => 'test'				
				)
			)
		);
		$this->dispatch('/notes/save-note');
        $this->assertController('notes');
        $this->assertAction('save-note');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'DATA' =>
				array(
					'ID' => '0',
					'TOPIC_ID' => '1',
					'CONTENTS' => 'test',
					'DESCRIPTION' => 'test'				
				)
			)
		);
		$this->dispatch('/notes/save-note');
        $this->assertController('notes');
        $this->assertAction('save-note');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'DATA' =>
				array(
					'ID' => '1',					
					'CONTENTS' => 'test',
					'DESCRIPTION' => 'test'				
				)
			)
		);
		$this->dispatch('/notes/save-note');
        $this->assertController('notes');
        $this->assertAction('save-note');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
    }
    
    
    public function testDeleteAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'id' => '1' )
		);
		$this->dispatch('/notes/delete');
        $this->assertController('notes');
        $this->assertAction('delete');                        
    	
    }
    
    
    public function testDeleteOtherUserNoteAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'id' => '4' )
		);
		$this->dispatch('/notes/delete');
        $this->assertController('error');
        $this->assertAction('error');
    	
    }
    
    
	public function testDeleteNoteWithInvalidIdAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array( 'id' => '0' )
		);
		$this->dispatch('/notes/delete');
        $this->assertController('error');
        $this->assertAction('error');
    	
    }
    
    
	public function testDeleteNoteWithoutIdAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array()
		);
		$this->dispatch('/notes/delete');
        $this->assertController('error');
        $this->assertAction('error');
    	
    }
    
    
    public function testLoadTopicsAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array('node' => 'root')
		);
		$this->dispatch('/notes/load-topics');
        $this->assertController('notes');
        $this->assertAction('load-topics');
        $this->assertContains('"id":"topic_1"', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array('node' => 'CSS')
		);
		$this->dispatch('/notes/load-topics');
        $this->assertController('notes');
        $this->assertAction('load-topics');
        $this->assertContains('"id":"topic_1"', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array()
		);
		$this->dispatch('/notes/load-topics');
        $this->assertController('notes');
        $this->assertAction('load-topics');        
    	
    }
    
    
    public function testGetTopicsAction(){

    	$this->resetRequest()->resetResponse();		
		$this->dispatch('/notes/get-topics');
        $this->assertController('notes');
        $this->assertAction('get-topics');
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());
    	
    }
    
    
    public function testLoadNoteAction(){
    	
    	$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array('id' => 1)
		);
		$this->dispatch('/notes/load-note');
        $this->assertController('notes');
        $this->assertAction('load-note');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array('id' => 0)
		);
		$this->dispatch('/notes/load-note');
        $this->assertController('notes');
        $this->assertAction('load-note');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array('id' => 4)
		);
		$this->dispatch('/notes/load-note');
        $this->assertController('notes');
        $this->assertAction('load-note');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array()
		);
		$this->dispatch('/notes/load-note');
        $this->assertController('notes');
        $this->assertAction('load-note');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
    }            
    
    
}