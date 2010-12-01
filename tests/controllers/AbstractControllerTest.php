<?php

require_once 'Zend/Test/PHPUnit/ControllerTestCase.php';
require_once 'Zend/Application.php';
require_once dirname(__FILE__) . '/../TestConfiguration.php';

abstract class AbstractControllerTest extends Zend_Test_PHPUnit_ControllerTestCase {			
	
	
	public function setUp(){
		
		defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . '/../../application' );
		
	    $this->bootstrap = new Zend_Application(
            'testing',
            APPLICATION_PATH . '/config/application.ini'
        );
                        
	    parent::setUp();
	    
	    TestConfiguration::setupUser();
	    TestConfiguration::setupDatabase();
	    
	}
	
		
	public function tearDown(){
		
				
		
	}
	
	
	
}