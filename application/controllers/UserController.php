<?php

class UserController extends Zend_Controller_Action {	
	
	
	public function init(){
		
		$this->_helper->layout->setLayout('plain');
						
	}			
	
	
	public function loginAction(){
		
		$user = Zend_Registry::get('user');
		$userManager = Zend_Registry::get('userManager');
		
		// CHECK IF USERS IS LOGGED IN		
		if( $user === false ){ 
		
			// CHECK FOR SSL -- RE-ENABLE
			if(isset($_SERVER["HTTPS"]) === false) {
				header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] );
				exit();
			}

			// IF NO SUCCESS URL IS SET THEN USE THE RTN_URL PARAMETER
			if( $userManager->getSuccessUrl() == "" && $this->_hasParam("rtn_url") ){
				$success_url = $this->_getParam("rtn_url");
			// IF NO SUCCESS URL AND RTN_URL PARAMETER IS SET THEN USE THE BASE URL	
			}else if( $userManager->getSuccessUrl() == "" ){
				$success_url = Zend_Controller_Front::getInstance()->getBaseUrl();
			// ELSE USE THE SUCCESS URL IN THE SESSION	
			}else{
				$success_url = $userManager->getSuccessUrl();
			}
		
			// IF LINK IS RELATIVE THEN USE NON SSL PORT FOR REDIRECT
			if( substr( $success_url,0,1) == "/" ){
				$success_url = "http://" . $_SERVER["SERVER_NAME"] . $success_url;
			}																				
			
			$this->view->success_url = $success_url;
			
			// CHECK FOR MOBILE
			if( $this->_helper->mobile->isMobile() === true || $this->_getParam('layout') == 'mobile' ){

				$this->view->success_url = $success_url . "/mobile";
				$this->_helper->layout()->setLayout('mobile');
				$this->_helper->viewRenderer->setNoRender();
				$this->view->failed = $this->_getParam('failed');
				$this->render('mobile-login');
								
			}
			
			
						
		}else{
			
			$this->_redirect("/");
			
		}
		
	}
	
	
	public function logoutAction(){
				
		$userManager = Zend_Registry::get('userManager');
		
		if( $userManager->loggedIn() === true ){
			$userManager->logout();
			$this->view->user = false;	
		}
		
		// CHECK FOR MOBILE
		if( $this->_helper->mobile->isMobile() === true || $this->_getParam('layout') == 'mobile' ){						
			$this->_redirect("/mobile");
		}else{
			$this->_redirect("/");
		}				
					
	}
	
	
	public function authAction(){		
		
		$userManager = Zend_Registry::get('userManager');
		
		if( $userManager->loggedIn() === false ){
			$userManager->auth( $this->_getParam("un"), $this->_getParam("ps") );			
		}			
		
		$auth = $userManager->loggedIn();
		
		if( $this->_helper->mobile->isMobile() === true ){ 
				
				$this->_helper->layout()->disableLayout();
				$this->_helper->viewRenderer->setNoRender();
				$this->render('iphone-login');

				if( $auth == true ){
					$this->_redirect( $this->_getParam('success_url') );
				}else{										
					$this->_redirect( "/user/login?failed=true&rtn_url=" . $this->_getParam('success_url') );
				}
				
		}else{			
				$this->_helper->json->sendJson( array("success" => $auth ) );				
		}	
		
	}
	
	
	public function statusAction(){
		
		$userManager = Zend_Registry::get('userManager');
		$this->_helper->json->sendJson( array("success" => $userManager->loggedIn() ) );
		
	}
	
	
	/*
	public function profileAction(){
		
		$user = Zend_Registry::get('user');		
		
		// CHECK IF USERS IS LOGGED IN		
		if( $user != false ){ 
		
			// CHECK FOR SSL
			if(isset($_SERVER["HTTPS"]) === false) {
				header("Location: https://" . $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"] );
				exit();
			}						
			
			$this->view->user = $user;			
			
		}else{
			
			$this->_redirect("/");			
			
		}
		
	}
	
	
	public function saveProfileAction(){
		
		$user = Zend_Registry::get('user');
		$userManager = Zend_Registry::get('userManager');				
		$errors = array();					
		
		if( $user != false ){ 
		
			// VALIDATE USERNAME
			if( $user->setUsername( $this->_getParam('username') ) === false ){
				$errors["username"] = "The username must be between 4 and 12 characters long and contain only alpha numeric characters";
			}else if( $userManager->checkUsername( $user ) == false ){
				$errors["username"] = "The username entered is not available";
			}			
			
			// UPDATE PASSWORD
			if( $this->_getParam('password') != "" && $this->_getParam('password_check') != "" ){
					
				// VALIDATE PASSWORD						
				if( $this->_getParam('password') != $this->_getParam('password_check') ){
					$errors["password"] = "The passwords entered do not match";			
					$errors["password_check"] = "The passwords entered do not match";
				}else if( $user->setPassword( $this->_getParam('password') ) === false ){
					$errors["password"] = "The passwords must be between 6 and 12 characters long and contain only alpha numeric characters";
				}					
				
			}				
			
			$user->setFirstName( $this->_getParam('first_name') );
			$user->setLastName( $this->_getParam('last_name') );
			$user->setGdataUsername( $this->_getParam('gdata_user') );
			$user->setGdataPassword( $this->_getParam('gdata_pass') );					
			
			if( count($errors) > 0 ){
				$this->_helper->json->sendJson( array("success" => false, "errors" => $errors ) );
			}else{
				$userManager->saveUser($user);
				$this->_helper->json->sendJson( array("success" => true ) );
			}
		
		}else{
			
			$this->_redirect("/");			
			
		}
		
	}
	*/
	
			
} 


?>
