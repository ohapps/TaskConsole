<?php

require_once 'PHPUnit/Framework.php';
require_once 'PHPUnit/TextUI/TestRunner.php';

require_once dirname(__FILE__) . '/models/NotesTest.php';
require_once dirname(__FILE__) . '/models/ProjectsTest.php';
require_once dirname(__FILE__) . '/models/CategoriesTest.php';
require_once dirname(__FILE__) . '/models/TasksTest.php';
require_once dirname(__FILE__) . '/models/TopicsTest.php';

require_once dirname(__FILE__) . '/controllers/IndexControllerTest.php';
require_once dirname(__FILE__) . '/controllers/MobileControllerTest.php';
require_once dirname(__FILE__) . '/controllers/NotesControllerTest.php';
require_once dirname(__FILE__) . '/controllers/ProjectsControllerTest.php';


class AllTests{
	
	public static function main(){
		PHPUnit_TextUI_TestRunner::run(self::suite());			
	}	
	
	public static function suite(){
		
		$suite = new PHPUnit_Framework_TestSuite('TaskConsole');
		
		// MODELS
		$suite->addTestSuite('NotesTest');		
		$suite->addTestSuite('ProjectsTest');
		$suite->addTestSuite('CategoriesTest');
		$suite->addTestSuite('TasksTest');
		$suite->addTestSuite('TopicsTest');
		
		// CONTROLLERS
		$suite->addTestSuite('IndexControllerTest');
		$suite->addTestSuite('MobileControllerTest');
		$suite->addTestSuite('NotesControllerTest');
		$suite->addTestSuite('ProjectsControllerTest');
		
		return $suite;
		
	}
	
}


?>