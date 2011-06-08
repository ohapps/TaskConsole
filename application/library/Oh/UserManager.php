<?php

require_once 'Base.php';
require_once 'Zend/Session/Namespace.php';
require_once 'Zend/Auth/Adapter/DbTable.php';

class Oh_UserManager extends Oh_Base {
					
	
	protected $_session;	
	
	
	public function __construct(){
		parent::__construct();		
		$this->_session = new Zend_Session_Namespace( $this->config->session->namespace );						
	}
	
	
	public function auth( $user, $pass ){
		
		try{ 
		
			$authAdapter = new Zend_Auth_Adapter_DbTable($this->conn, 'users', 'username', 'password');
			$authAdapter->setIdentity($user)->setCredential( md5( $pass ) );		
			$result = $authAdapter->authenticate();
			
			if($result->isValid()){				
				//$user = $authAdapter->getResultRowObject();							
				return $this->login($user);								
			}else{
				$this->_session->failed_attempts += 1;	
				return false;
			}	
		     
		}catch(Exception $e){
			return false;
		}
			  		
	}
					
	
	public function chk_auth( $success_url, $direct_url = false ){				
		if ( $this->_session->authenticated == false ){
			$this->_session->success_url = $success_url;
			if ( $direct_url ){
				header( 'Location: '. $this->config->session->login_url .'?rtn_url='.$success_url ) ;				
			}else{
				header( 'Location: '. $this->config->session->login_url ) ;
			}
		}		
	}	
	
	
	public function auth_user( $user, $pass, $success_url ){		
		if (  $this->auth( $user, $pass ) ){									
			header( 'Location: '. $success_url ) ;							
		}else{
			$this->_session->success_url = $success_url;			
			header( 'Location: '. $this->config->session->login_url ) ;
		}				
	}	
		
	
	public function login($user){		
		
		// CREATE USER OBJECT		
		$ohUser = $this->getUserByUsername($user);		
		
		if( $ohUser != false ){
		
			// SET SESSION VARIABLES
			$this->_session->user = $ohUser;		
			$this->_session->authenticated = true;
			$this->_session->failed_attempts = 0;
					
			//setcookie($this->config->session->namespace,$ohUser->getId(), 0, "/");
		
		}
		
		return $ohUser;
				
	}	
	
	
	public function logout(){				
		Zend_Session::destroy(true);
		//setcookie($this->config->session->namespace,"", 0, "/");		
	}
	
	
	public function getSuccessUrl(){
		return $this->_session->success_url;		
	}											
	

	public function getLogoutUrl(){
		return $this->config->session->logout_url;
	}			
	
	
	public function loggedIn(){
		if( $this->_session->authenticated === true ){
			return true;
		}else{
			return false;
		}		
	}
	
	
	public function getCurrentUser(){
		if( $this->loggedIn() === true ){
			return $this->_session->user;
		}else{
			return false;
		}
	}
	
	
	public function getUserByUsername($username){
		
		$results = $this->conn->fetchAll( "SELECT * FROM users where username = ?", $username );
		
		if( count($results) == 1 ){
			$ohUser = new Oh_User($this);
			$ohUser->setFromArray($results[0]);
			return $ohUser;
		}
		
		return false;
		
	}
	
	
	public function getUserById($id){
		
		$results = $this->conn->fetchAll( "SELECT * FROM users where id = ?", $id );
		
		if( count($results) == 1 ){
			$ohUser = new Oh_User($this);
			$ohUser->setFromArray($results[0]);
			return $ohUser;
		}
		
		return false;
		
	}
	
	
	public function saveUser(Oh_User $user){
				
		$this->conn->update(
			'users',
			$user->toArray(),
			'ID = ' . $user->getId()
		);
				
	}
	
	
	public function checkUsername(Oh_User $user){
		
		if( count( $this->conn->fetchAll( 'select * from users where USERNAME = ? and ID != ?', array( $user->getUsername(), $user->getId() ) ) ) > 0 ){
			return false;
		}else{
			return true;
		}
		
	}
	
	
}


?>
