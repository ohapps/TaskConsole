<?php
/**
 * My new Zend Framework project
 * 
 * @author  
 * @version 
 */

// Define path to application directory
define('APPLICATION_PATH', dirname(__FILE__) . '/application' );

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') : 'development'));

// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    APPLICATION_PATH . '/library',
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';  

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV, 
    APPLICATION_PATH . '/config/application.ini'    
);
$application->bootstrap()
            ->run();