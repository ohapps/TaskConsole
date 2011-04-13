<?php
    
require_once 'Zend/Db.php';
require_once 'Zend/Config.php';


class Oh_Base {
				
	protected $conn;
	protected $config;						
	
	function __construct(){		
		
		$this->config = new Zend_Config(
			array(
	
				'database' 	=> array(
				
						'adapter' 		=> 		'Mysqli',
						'host'			=>		'localhost',
						'username'		=>		'tc_user',
						'password'		=>		't3ch13',
						'dbname'		=>		'taskconsole'
				
				),
				
				'session'	=> array(
				
						'namespace'		=>		'ohapps',
						'login_url'		=>		'/TaskConsole/user/login',
						'logout_url'	=>		'/TaskConsole/user/logout'						
				
				)
			
			)		
		);							
		
		$this->conn = Zend_Db::factory($this->config->database->adapter, array(
    				'host'     => $this->config->database->host,
    				'username' => $this->config->database->username,
    				'password' => $this->config->database->password,    				
    				'dbname'   => $this->config->database->dbname
		));			
		
	}
		
	
}
	
?>
