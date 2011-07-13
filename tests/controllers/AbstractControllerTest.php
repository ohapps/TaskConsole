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
	
		
	public function tearDown(){
		
				
		
	}
	
	
	
}