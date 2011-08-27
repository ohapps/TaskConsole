<?php

require_once 'AbstractControllerTest.php';

class TasksControllerTest extends AbstractControllerTest {
	
	
	public function testSavetaskAction(){
    	
    	$today = new Zend_Date();
    	$config = $this->bootstrap->getOption('app');    	    	
    	
    	// successful update
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '1',
				'PROJECT_ID' => '1',
				'DUE_DATE' => $today->toString($config['date']['dbFormat']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7',
				'CATEGORIES' => '1,2',
				'ADD_TO_QUEUE' => '1'
			)
		);
		$this->dispatch('/tasks/save');
        $this->assertController('tasks');
        $this->assertAction('save');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // successful insert
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '1',
				'PROJECT_ID' => '1',
				'DUE_DATE' => $today->toString($config['date']['dbFormat']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7',
				'CATEGORIES' => '1,2',
				'ADD_TO_QUEUE' => '1'
			)
		);
		$this->dispatch('/tasks/save');
        $this->assertController('tasks');
        $this->assertAction('save');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // fail invalid priority
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '4',
				'PROJECT_ID' => '1',
				'DUE_DATE' => $today->toString($config['date']['dbFormat']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7'
			)
		);
		$this->dispatch('/tasks/save');
        $this->assertController('tasks');
        $this->assertAction('save');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
    	
        // fail invalid project id
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '3',
				'PROJECT_ID' => '0',
				'DUE_DATE' => $today->toString($config['date']['dbFormat']),
				'RECUR_UNIT_TYPE' => 'days',
				'RECUR_UNITS' => '7'
			)
		);
		$this->dispatch('/tasks/save');
        $this->assertController('tasks');
        $this->assertAction('save');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // fail update task for another user
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '5',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '3',
				'PROJ_ID' => '4',
				'DUE_DATE' => $today->toString($config['date']['dbFormat']),
				'RECUR_UNIT_TYPE' => '',
				'RECUR_UNITS' => ''
			)
		);
		$this->dispatch('/tasks/save');
        $this->assertController('tasks');
        $this->assertAction('save');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());                
        
        // fail invalid recur unit type
        $this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'ID' => '1',
				'DESCRIPTION' => 'Test',
				'PRIORITY_ID' => '3',
				'PROJ_ID' => '1',
				'DUE_DATE' => $today->toString($config['date']['dbFormat']),
				'RECUR_UNIT_TYPE' => 'hours',
				'RECUR_UNITS' => '7'
			)
		);
		$this->dispatch('/tasks/save');
        $this->assertController('tasks');
        $this->assertAction('save');        
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
    
    public function testDeletetasksAction(){
    	
    	// successful update
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => '1'
			)
		);
		$this->dispatch('/tasks/delete');
        $this->assertController('tasks');
        $this->assertAction('delete');        
        $this->assertContains('"success":true', $this->getResponse()->getBody());
                
    	// successful update
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 2,
				'data' => 3
			)
		);
		$this->dispatch('/tasks/delete');
        $this->assertController('tasks');
        $this->assertAction('delete');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed delete another user tasks
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5
			)
		);
		$this->dispatch('/tasks/delete');
        $this->assertController('tasks');
        $this->assertAction('delete');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0
			)
		);
		$this->dispatch('/tasks/delete');
        $this->assertController('tasks');
        $this->assertAction('delete');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
	
	
public function testLoadTaskDetailAction(){

    	// successful load
    	$this->request->setMethod('POST')->setPost(
			array(
				"id" => 1
			)
		);
		$this->dispatch('/tasks/load');
        $this->assertController('tasks');
        $this->assertAction('load');
        $this->assertContains('"ID":"1"', $this->getResponse()->getBody());
        
        // fail no id parameter
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array()
		);
		$this->dispatch('/tasks/load');
        $this->assertController('tasks');
        $this->assertAction('load');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // fail not user task
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				"id" => 5
			)
		);
		$this->dispatch('/tasks/load');
        $this->assertController('tasks');
        $this->assertAction('load');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // fail invalid id
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				"id" => 0
			)
		);
		$this->dispatch('/tasks/load');
        $this->assertController('tasks');
        $this->assertAction('load');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
                 	
    }
    
    
	public function testUsertasksAction(){
    	
    	// successful load of pending tasks
    	$this->request->setMethod('POST')->setPost(
			array(
				'start' => '0',
				'limit' => '50',
				'status' => 'pending',
				'priorities' => '1,2,3'
			)
		);
		$this->dispatch('/tasks/user-tasks');
        $this->assertController('tasks');
        $this->assertAction('user-tasks');
        $this->assertContains('"ID":"2"', $this->getResponse()->getBody());

        
        // successful with project id 2
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				'project' => '2',
				'start' => '0',
				'limit' => '50',
				'status' => 'pending',
				'priorities' => '1,2,3'
			)
		);
		$this->dispatch('/tasks/user-tasks');
        $this->assertController('tasks');
        $this->assertAction('user-tasks');
        $this->assertContains('"ID":"2"', $this->getResponse()->getBody());
                
        // successful with category id 1 and project id 2
        $this->resetRequest()->resetResponse();
    	$this->request->setMethod('POST')->setPost(
			array(
				'category' => '1',
				'project' => '2',
				'start' => '0',
				'limit' => '50',
				'status' => 'pending',
				'priorities' => '1,2,3'
			)
		);
		$this->dispatch('/tasks/user-tasks');
        $this->assertController('tasks');
        $this->assertAction('user-tasks');
        $this->assertContains('"ID":"2"', $this->getResponse()->getBody());                        
    	
    }
    
    
	public function testMarkcompleteAction(){
    	
    	// successful mark complete
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 1
			)
		);
		$this->dispatch('/tasks/mark-complete');
        $this->assertController('tasks');
        $this->assertAction('mark-complete');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful mark complete
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 2,
				'data' => 3
			)
		);
		$this->dispatch('/tasks/mark-complete');
        $this->assertController('tasks');
        $this->assertAction('mark-complete');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed mark complete another user tasks
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5
			)
		);
		$this->dispatch('/tasks/mark-complete');
        $this->assertController('tasks');
        $this->assertAction('mark-complete');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0
			)
		);
		$this->dispatch('/tasks/mark-complete');
        $this->assertController('tasks');
        $this->assertAction('mark-complete');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
    
	public function testMarkIncompleteAction(){
    	
    	// successful mark incomplete
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 3
			)
		);
		$this->dispatch('/tasks/mark-incomplete');
        $this->assertController('tasks');
        $this->assertAction('mark-incomplete');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful mark incomplete
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 3,
				'data' => 4
			)
		);
		$this->dispatch('/tasks/mark-incomplete');
        $this->assertController('tasks');
        $this->assertAction('mark-incomplete');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed mark complete another user tasks
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5
			)
		);
		$this->dispatch('/tasks/mark-incomplete');
        $this->assertController('tasks');
        $this->assertAction('mark-incomplete');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0
			)
		);
		$this->dispatch('/tasks/mark-incomplete');
        $this->assertController('tasks');
        $this->assertAction('mark-incomplete');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }

    
	public function testApplyCategoryAction(){
    	
    	// successful apply category
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 3,
				'id' => 1
			)
		);
		$this->dispatch('/tasks/apply-category');
        $this->assertController('tasks');
        $this->assertAction('apply-category');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful apply catgory
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => array(3,4),
				'id' => 1
			)
		);
		$this->dispatch('/tasks/apply-category');
        $this->assertController('tasks');
        $this->assertAction('apply-category');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed add category to another user's tasks
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5,
				'id' => 1			
			)
		);
		$this->dispatch('/tasks/apply-category');
        $this->assertController('tasks');
        $this->assertAction('apply-category');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0,
				'id' => 1
			)
		);
		$this->dispatch('/tasks/apply-category');
        $this->assertController('tasks');
        $this->assertAction('apply-category');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed apply another user's category
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 1,
				'id' => 5
			)
		);
		$this->dispatch('/tasks/apply-category');
        $this->assertController('tasks');
        $this->assertAction('apply-category');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid category id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 1,
				'id' => 0
			)
		);
		$this->dispatch('/tasks/apply-category');
        $this->assertController('tasks');
        $this->assertAction('apply-category');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
    
	public function testRemoveCategoryAction(){
    	
    	// successful remove category
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 1,
				'id' => 1
			)
		);
		$this->dispatch('/tasks/remove-category');
        $this->assertController('tasks');
        $this->assertAction('remove-category');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful remove catgory
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => array(1,2),
				'id' => 1
			)
		);
		$this->dispatch('/tasks/remove-category');
        $this->assertController('tasks');
        $this->assertAction('remove-category');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed removing category to another user's tasks
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5,
				'id' => 1			
			)
		);
		$this->dispatch('/tasks/remove-category');
        $this->assertController('tasks');
        $this->assertAction('remove-category');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0,
				'id' => 1
			)
		);
		$this->dispatch('/tasks/remove-category');
        $this->assertController('tasks');
        $this->assertAction('remove-category');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed apply another user's category
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 1,
				'id' => 5
			)
		);
		$this->dispatch('/tasks/remove-category');
        $this->assertController('tasks');
        $this->assertAction('remove-category');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid category id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 1,
				'id' => 0
			)
		);
		$this->dispatch('/tasks/remove-category');
        $this->assertController('tasks');
        $this->assertAction('remove-category');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
    }
    
    
	public function testSetPriorityAction(){
    	
    	// successful set priority
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 1,
				'id' => 1
			)
		);
		$this->dispatch('/tasks/set-priority');
        $this->assertController('tasks');
        $this->assertAction('set-priority');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful set priority
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => array(1,2),
				'id' => 1
			)
		);
		$this->dispatch('/tasks/set-priority');
        $this->assertController('tasks');
        $this->assertAction('set-priority');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed set priority of another user's task
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5,
				'id' => 1			
			)
		);
		$this->dispatch('/tasks/set-priority');
        $this->assertController('tasks');
        $this->assertAction('set-priority');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0,
				'id' => 1
			)
		);
		$this->dispatch('/tasks/set-priority');
        $this->assertController('tasks');
        $this->assertAction('set-priority');
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid priority
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 1,
				'id' => 5
			)
		);
		$this->dispatch('/tasks/set-priority');
        $this->assertController('tasks');
        $this->assertAction('set-priority');
        $this->assertContains('"success":false', $this->getResponse()->getBody());                
        
    }
    
    
	public function testAddToQueueAction(){
    	
    	// successful add to queue
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 1
			)
		);
		$this->dispatch('/tasks/add-to-queue');
        $this->assertController('tasks');
        $this->assertAction('add-to-queue');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful add to queue
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => array(1,2)
			)
		);
		$this->dispatch('/tasks/add-to-queue');
        $this->assertController('tasks');
        $this->assertAction('add-to-queue');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed adding another user's task to queue
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5		
			)
		);
		$this->dispatch('/tasks/add-to-queue');
        $this->assertController('tasks');
        $this->assertAction('add-to-queue');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0
			)
		);
		$this->dispatch('/tasks/add-to-queue');
        $this->assertController('tasks');
        $this->assertAction('add-to-queue');
        $this->assertContains('"success":false', $this->getResponse()->getBody());       
        
    }
    
    
	public function testRemoveFromQueueAction(){
    	
    	// successful add to queue
    	$this->request->setMethod('POST')->setPost(
			array(
				'data' => 1
			)
		);
		$this->dispatch('/tasks/remove-from-queue');
        $this->assertController('tasks');
        $this->assertAction('remove-from-queue');
        $this->assertContains('"success":true', $this->getResponse()->getBody());
    	
        // successful add to queue
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => array(1,2)
			)
		);
		$this->dispatch('/tasks/remove-from-queue');
        $this->assertController('tasks');
        $this->assertAction('remove-from-queue');  
        $this->assertContains('"success":true', $this->getResponse()->getBody());
        
        // failed adding another user's task to queue
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 5		
			)
		);
		$this->dispatch('/tasks/remove-from-queue');
        $this->assertController('tasks');
        $this->assertAction('remove-from-queue');  
        $this->assertContains('"success":false', $this->getResponse()->getBody());
        
        // failed invalid task id
    	$this->resetRequest()->resetResponse();
        $this->request->setMethod('POST')->setPost(
			array(
				'data' => 0
			)
		);
		$this->dispatch('/tasks/remove-from-queue');
        $this->assertController('tasks');
        $this->assertAction('remove-from-queue');
        $this->assertContains('"success":false', $this->getResponse()->getBody());       
        
    }
    
    
}