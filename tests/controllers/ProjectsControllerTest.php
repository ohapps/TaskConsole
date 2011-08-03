<?php

require_once 'AbstractControllerTest.php';

class ProjectsControllerTest extends AbstractControllerTest {									   
    
    
	public function testSavecatAction(){				
		
		$this->request->setMethod('POST')->setPost(array('ID' => '1','DESCRIPTION' => 'test'));
		$this->dispatch('/projects/savecat');
        $this->assertController('projects');
        $this->assertAction('savecat');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
                        
		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array('ID' => '0','DESCRIPTION' => 'test'));
		$this->dispatch('/projects/savecat');
        $this->assertController('projects');
        $this->assertAction('savecat');
        $this->assertContains('"success":false', $this->getResponse()->getBody());        
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array('ID' => '5','DESCRIPTION' => 'test'));
		$this->dispatch('/projects/savecat');
        $this->assertController('projects');
        $this->assertAction('savecat');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1',
				'DESCRIPTION' => 'aaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaaa'
				)
		);
		$this->dispatch('/projects/savecat');
        $this->assertController('projects');
        $this->assertAction('savecat');
        $this->assertContains('"success":true', $this->getResponse()->getBody());                
                
    }
    
    	        
	public function testDeletecatAction(){
        
		$this->request->setMethod('POST')->setPost(array('ID' => '1'));
		$this->dispatch('/projects/deletecat');
        $this->assertController('projects');
        $this->assertAction('deletecat');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());        
                
		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array('ID' => '0'));
		$this->dispatch('/projects/deletecat');
        $this->assertController('projects');
        $this->assertAction('deletecat');
        $this->assertContains('"success":false', $this->getResponse()->getBody());                               
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(array('ID' => '5'));
		$this->dispatch('/projects/deletecat');
        $this->assertController('projects');
        $this->assertAction('deletecat');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
                
    }
    
    
	public function testSaveprojectAction(){
        
		// successful update
		$this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1',
				'DESCRIPTION' => 'Test',
				'COMMENTS' => 'test',
				'AUTO_COMPLETE' => '1',
				'STATUS' => 'complete',
				'CATEGORIES' => '1,2'
			)
		);
		$this->dispatch('/projects/saveproject');
        $this->assertController('projects');
        $this->assertAction('saveproject');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());                        		
        
        // successful insert
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'COMMENTS' => 'test',
				'AUTO_COMPLETE' => '1',
				'STATUS' => 'active',
				'CATEGORIES' => '1'								
			)
		);
		$this->dispatch('/projects/saveproject');
        $this->assertController('projects');
        $this->assertAction('saveproject');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());

        // invalid project category id
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'COMMENTS' => 'test',
				'AUTO_COMPLETE' => '1',
				'STATUS' => 'active',
				'CATEGORIES' => '9'								
			)
		);
		$this->dispatch('/projects/saveproject');
        $this->assertController('projects');
        $this->assertAction('saveproject');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // project id doesn't belong to current user
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '3',
				'DESCRIPTION' => 'Test',
				'COMMENTS' => 'test',
				'AUTO_COMPLETE' => '1',
				'STATUS' => 'active',
				'CATEGORIES' => '1'								
			)
		);
		$this->dispatch('/projects/saveproject');
        $this->assertController('projects');
        $this->assertAction('saveproject');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // invalid project id
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '0',
				'DESCRIPTION' => 'Test',
				'COMMENTS' => 'test',
				'AUTO_COMPLETE' => '1',
				'STATUS' => 'active',
				'CATEGORIES' => '1'								
			)
		);
		$this->dispatch('/projects/saveproject');
        $this->assertController('projects');
        $this->assertAction('saveproject');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
        
    public function testDeleteprojectAction(){
    	
    	// successful delete
        $this->request->setMethod('POST')->setPost(
			array(
				'id' => '1'
			)
		);
		$this->dispatch('/projects/delete-project');
        $this->assertController('projects');
        $this->assertAction('delete-project');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // fail deleting another users project
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'id' => '5'
			)
		);
		$this->dispatch('/projects/delete-project');
        $this->assertController('projects');
        $this->assertAction('delete-project');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
        // fail invalid project id
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'id' => '20'
			)
		);
		$this->dispatch('/projects/delete-project');
        $this->assertController('projects');
        $this->assertAction('delete-project');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
                           
    public function testUsercatsAction(){
    	
    	$this->dispatch('/projects/usercats');
        $this->assertController('projects');
        $this->assertAction('usercats');  
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());
    	
    }
    
    
    public function testUserprojectsAction(){
    	
    	$this->dispatch('/projects/userprojects');
        $this->assertController('projects');
        $this->assertAction('userprojects');  
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());	
    	
    }
    
    
    public function testUserProjectsPagedAction(){
    	
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'start' => '0',
				'limit' => '25'
			)
		);
    	$this->dispatch('/projects/user-projects-paged');
        $this->assertController('projects');
        $this->assertAction('user-projects-paged');  
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());	
    	
    }

                                       
    public function testLoadtreeAction(){
    	
    	$this->dispatch('/projects/loadtree');
        $this->assertController('projects');
        $this->assertAction('loadtree');
        $this->assertContains('"id":"project_1"', $this->getResponse()->getBody());
    	
    }
    
    
    public function testPrioritiesAction(){
    	
    	$this->dispatch('/projects/priorities');
        $this->assertController('projects');
        $this->assertAction('priorities');
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());
    	
    }
    
    
}