<?php

require_once 'AbstractControllerTest.php';

class MobileControllerTest extends AbstractControllerTest {
	
	
	public function testIndexAction(){
		
		$this->dispatch('/mobile');
        $this->assertController('mobile');
        $this->assertAction('index');
		
	}
	
	
	public function testProjectsAction(){
		
		$this->dispatch('/mobile/projects');
        $this->assertController('mobile');
        $this->assertAction('projects');
		
	}
	
	
	public function testTasksAction(){
		
		$this->dispatch('/mobile/tasks');
        $this->assertController('mobile');
        $this->assertAction('tasks');
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array('category' => '1')
		);
		$this->dispatch('/mobile/tasks');
        $this->assertController('mobile');
        $this->assertAction('tasks');
        
        $this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array('project' => '1')
		);
		$this->dispatch('/mobile/tasks');
        $this->assertController('mobile');
        $this->assertAction('tasks');
		
	}
	
	
	public function testTaskDetailAction(){
		
		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'category' => '1',
				'project' => '1',
				'id' => '1'
			)
		);
		$this->dispatch('/mobile/task-detail');
        $this->assertController('mobile');
        $this->assertAction('task-detail');
                                		
	}
	
	
	public function testTaskDetailWithOtherUserTaskAction(){
		
		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'category' => '1',
				'project' => '1',
				'id' => '4'
			)
		);
		$this->dispatch('/mobile/task-detail');
        $this->assertController('error');
        $this->assertAction('error');
		
	}
	
	
	public function testTaskDetailWithInvalidTaskAction(){
		
		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'category' => '1',
				'project' => '1',
				'id' => '6'
			)
		);
		$this->dispatch('/mobile/task-detail');
        $this->assertController('error');
        $this->assertAction('error');
		
	}
	
	
	public function testMarkTaskCompleteAction(){

		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'category' => '1',
				'project' => '1',
				'id' => '1'
			)
		);
		$this->dispatch('/mobile/mark-task-complete');
        $this->assertController('mobile');
        $this->assertAction('tasks');
		
	}
	
	
	public function testMarkTaskCompleteWithOtherUserTaskAction(){

		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'category' => '1',
				'project' => '1',
				'id' => '4'
			)
		);
		$this->dispatch('/mobile/mark-task-complete');
        $this->assertController('error');
        $this->assertAction('error');
		
	}
	
	
	public function testMarkTaskCompleteWithInvalidTaskAction(){

		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'category' => '1',
				'project' => '1',
				'id' => '6'
			)
		);
		$this->dispatch('/mobile/mark-task-complete');
        $this->assertController('error');
        $this->assertAction('error');
		
	}
	
	
	public function testNotesAction(){
		
		$this->dispatch('/mobile/notes');
        $this->assertController('mobile');
        $this->assertAction('notes');
		
	}
	
	
	public function testViewNoteAction(){

		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'id' => '1'
			)
		);
		$this->dispatch('/mobile/view-note');
        $this->assertController('mobile');
        $this->assertAction('view-note');
		
	}
	
	
	public function testViewNoteWithOtherUserNoteAction(){

		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'id' => '4'
			)
		);
		$this->dispatch('/mobile/view-note');
        $this->assertController('error');
        $this->assertAction('error');
		
	}
	
	
	public function testViewNoteWithInvalidNoteIdAction(){

		$this->resetRequest()->resetResponse();
		$this->request->setMethod('POST')->setPost(
			array(
				'id' => '6'
			)
		);
		$this->dispatch('/mobile/view-note');
        $this->assertController('error');
        $this->assertAction('error');
		
	}
	
	
}