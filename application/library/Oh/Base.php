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
						'username'		=>		'oh_user',
						'password'		=>		'Quc4RGC9CFNhLcgdL1qdO8wcp5P7622TjGTdNxnyG6I=',
						'dbname'		=>		'ohapp'
				
				),
				
				'session'	=> array(
				
						'namespace'		=>		'ohapps',
						'login_url'		=>		'/home/user/login',
						'logout_url'	=>		'/home/user/logout'						
				
				)
			
			)		
		);							
		
		$this->conn = Zend_Db::factory($this->config->database->adapter, array(
    				'host'     => $this->config->database->host,
    				'username' => $this->config->database->username,
    				'password' => Oh_Crypt::decrypt( $this->config->database->password ),    				
    				'dbname'   => $this->config->database->dbname
		));			
		
	}
		
	
}
	
?>
