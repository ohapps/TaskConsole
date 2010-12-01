<?php

require_once 'AbstractControllerTest.php';

class ProjectsControllerTest extends AbstractControllerTest {						
	
	
	public function testIndexAction(){
        
		$this->dispatch('/projects');
        $this->assertController('projects');
        $this->assertAction('index');
                
    }   
    
    
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
		$this->request->setMethod('POST')->setPost(array('ID' => '4'));
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
				'CATEGORY' => '1',
				'COMPLETE' => '0'
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
				'CATEGORY' => '1',
				'COMPLETE' => '0'
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
				'DESCRIPTION' => 'Test error',
				'COMMENTS' => 'test',
				'CATEGORY' => '9',
				'COMPLETE' => '0'
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
				'ID' => '',
				'DESCRIPTION' => 'Test error',
				'COMMENTS' => 'test',
				'CATEGORY' => '4',
				'COMPLETE' => '0'
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
				'ID' => '6',
				'DESCRIPTION' => 'Test error',
				'COMMENTS' => 'test',
				'CATEGORY' => '1',
				'COMPLETE' => '0'
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
				'ID' => '1'
			)
		);
		$this->dispatch('/projects/deleteproject');
        $this->assertController('projects');
        $this->assertAction('deleteproject');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // fail deleting another users project
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '4'
			)
		);
		$this->dispatch('/projects/deleteproject');
        $this->assertController('projects');
        $this->assertAction('deleteproject');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
        // fail invalid project id
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '5'
			)
		);
		$this->dispatch('/projects/deleteproject');
        $this->assertController('projects');
        $this->assertAction('deleteproject');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
    
    public function testSavetaskAction(){
    	
    	$today = new Zend_Date();
    	$config = $this->bootstrap->getOption('app');    	    	
    	
    	// successful update
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '1',
				'PROJ_ID' => '1',
				'DUE_DATE' => $today->toString($config['date_format']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7',
				'DISP_ON_GCAL' => '0'
			)
		);
		$this->dispatch('/projects/savetask');
        $this->assertController('projects');
        $this->assertAction('savetask');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // successful insert
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '1',
				'PROJ_ID' => '1',
				'DUE_DATE' => $today->toString($config['date_format']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7',
				'DISP_ON_GCAL' => '0'
			)
		);
		$this->dispatch('/projects/savetask');
        $this->assertController('projects');
        $this->assertAction('savetask');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // fail invalid priority
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '4',
				'PROJ_ID' => '1',
				'DUE_DATE' => $today->toString($config['date_format']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7',
				'DISP_ON_GCAL' => '0'
			)
		);
		$this->dispatch('/projects/savetask');
        $this->assertController('projects');
        $this->assertAction('savetask');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
        // fail invalid project id
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '3',
				'PROJ_ID' => '6',
				'DUE_DATE' => $today->toString($config['date_format']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7',
				'DISP_ON_GCAL' => '0'
			)
		);
		$this->dispatch('/projects/savetask');
        $this->assertController('projects');
        $this->assertAction('savetask');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // fail update task for another user
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '3',
				'PROJ_ID' => '4',
				'DUE_DATE' => $today->toString($config['date_format']),
				'RECUR_UNIT_TYPE' => '',
				'RECUR_UNITS' => '',
				'DISP_ON_GCAL' => '0'
			)
		);
		$this->dispatch('/projects/savetask');
        $this->assertController('projects');
        $this->assertAction('savetask');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());                
        
        // fail invalid recur unit type
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '3',
				'PROJ_ID' => '1',
				'DUE_DATE' => $today->toString($config['date_format']),
				'RECUR_UNIT_TYPE' => 'hours',
				'RECUR_UNITS' => '7',
				'DISP_ON_GCAL' => '0'
			)
		);
		$this->dispatch('/projects/savetask');
        $this->assertController('projects');
        $this->assertAction('savetask');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
    
    public function testDeletetasksAction(){
    	
    	// successful update
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => '1'
			)
		);
		$this->dispatch('/projects/deletetasks');
        $this->assertController('projects');
        $this->assertAction('deletetasks');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
                
    	// successful update
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 2,
				'data' => 3
			)
		);
		$this->dispatch('/projects/deletetasks');
        $this->assertController('projects');
        $this->assertAction('deletetasks');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed delete another user tasks
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 4
			)
		);
		$this->dispatch('/projects/deletetasks');
        $this->assertController('projects');
        $this->assertAction('deletetasks');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5
			)
		);
		$this->dispatch('/projects/deletetasks');
        $this->assertController('projects');
        $this->assertAction('deletetasks');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
    
    public function testMarkcompleteAction(){
    	
    	// successful mark complete
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 1
			)
		);
		$this->dispatch('/projects/markcomplete');
        $this->assertController('projects');
        $this->assertAction('markcomplete');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful mark complete
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 2,
				'data' => 3
			)
		);
		$this->dispatch('/projects/markcomplete');
        $this->assertController('projects');
        $this->assertAction('markcomplete');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed mark complete another user tasks
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 4
			)
		);
		$this->dispatch('/projects/markcomplete');
        $this->assertController('projects');
        $this->assertAction('markcomplete');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5
			)
		);
		$this->dispatch('/projects/markcomplete');
        $this->assertController('projects');
        $this->assertAction('markcomplete');
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
    
    
    public function testUserProjectsAllAction(){
    	
    	$this->dispatch('/projects/user-projects-all');
        $this->assertController('projects');
        $this->assertAction('user-projects-all');  
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());	
    	
    }

    
    public function testUsertasksAction(){
    	
    	// successful with non parameters
    	$this->request->setMethod('POST')->setPost(
			array()
		);
		$this->dispatch('/projects/usertasks');
        $this->assertController('projects');
        $this->assertAction('usertasks');
        $this->assertContains('"ID":"2"', $this->getResponse()->getBody());
                
        // successful with project id 2
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				'project' => '2'
			)
		);
		$this->dispatch('/projects/usertasks');
        $this->assertController('projects');
        $this->assertAction('usertasks');
        $this->assertContains('"ID":"2"', $this->getResponse()->getBody());
        
        // successful with category id 2 and project id 2
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				'category' => '2',
				'project' => '2'
			)
		);
		$this->dispatch('/projects/usertasks');
        $this->assertController('projects');
        $this->assertAction('usertasks');
        $this->assertContains('"ID":"2"', $this->getResponse()->getBody());                
    	
    }
    
    
    public function testGetUserHighPriorityTasksAction(){
    	
    	$this->dispatch('/projects/get-user-high-priority-tasks');
        $this->assertController('projects');
        $this->assertAction('get-user-high-priority-tasks');
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());
    	
    }
    
    
    public function testGetUserNearDueTasksAction(){
    	
    	$this->dispatch('/projects/get-user-near-due-tasks');
        $this->assertController('projects');
        $this->assertAction('get-user-near-due-tasks');
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());    	
    	
    }
    
    
    public function testLoadTaskDetailAction(){

    	// successful load
    	$this->request->setMethod('POST')->setPost(
			array(
				"id" => 1
			)
		);
		$this->dispatch('/projects/load-task-detail');
        $this->assertController('projects');
        $this->assertAction('load-task-detail');
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());
        
        // fail no id parameter
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array()
		);
		$this->dispatch('/projects/load-task-detail');
        $this->assertController('projects');
        $this->assertAction('load-task-detail');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // fail not user task
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				"id" => 4
			)
		);
		$this->dispatch('/projects/load-task-detail');
        $this->assertController('projects');
        $this->assertAction('load-task-detail');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // fail invalid id
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				"id" => 9
			)
		);
		$this->dispatch('/projects/load-task-detail');
        $this->assertController('projects');
        $this->assertAction('load-task-detail');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // fail invalid id
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				"id" => 9
			)
		);
		$this->dispatch('/projects/load-task-detail');
        $this->assertController('projects');
        $this->assertAction('load-task-detail');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
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