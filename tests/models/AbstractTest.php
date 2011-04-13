<?php

define('APPLICATION_PATH', dirname(__FILE__) . '/../../application' );

set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . '/library',
    APPLICATION_PATH . '/models',
    get_include_path(),
)));

require_once 'PHPUnit/Framework/TestCase.php';
require_once 'Zend/Application.php';
require_once dirname(__FILE__) . '/../TestConfiguration.php';

 
abstract class AbstractTest extends PHPUnit_Framework_TestCase {
	
	
	protected $bootstrap;
	protected $testUserId = 1;
	
	
	public function setUp(){
		
		defined('APPLICATION_PATH') || define('APPLICATION_PATH', dirname(__FILE__) . '/../../application' );
		
	    $application = new Zend_Application(
            'testing',
            APPLICATION_PATH . '/config/application.ini'
        );        
        
        $this->bootstrap = $application->getBootstrap();
        
        $this->bootstrap->bootstrap('doctrine');
                        
        TestConfiguration::setupDatabase();
        
	    parent::setUp();
	    	
	}

	
	public function tearDown(){
		
		
		
	}
	
	
}