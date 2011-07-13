<?php

define('APPLICATION_PATH', dirname(__FILE__) . '/../../application' );

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . '/library',
    APPLICATION_PATH . '/models',
    get_include_path(),
)));

require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';
require_once 'Zend/Application.php';
require_once dirname(__FILE__) . '/../TestConfiguration.php';

abstract class AbstractControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {			
	
	
	public function setUp(){				
		
	    $this->bootstrap = new Zend_Application(
            'testing',
            APPLICATION_PATH . '/config/application.ini'
        );
                        
	    parent::setUp();
	    
	    TestConfiguration::setupDatabase();
	    
	    
	}
	
	
	public function login(){
		
		$userManager = Zend_Registry::get('userManager');
		$userManager->auth( 'demo', 'demo' );
		
		/*
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
        */                
		
	}
	
		
	public function tearDown(){
		
				
		
	}
	
	
	
}