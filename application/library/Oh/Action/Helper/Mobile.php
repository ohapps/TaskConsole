<?php

class Oh_Action_Helper_Mobile extends Zend_Controller_Action_Helper_Abstract {
	
	
	public function isIphone(){
		if(isset($_SERVER['HTTP_USER_AGENT'])){
			
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'iPhone') !== FALSE) { 
				return true;			
			}						
			
		}
		return false;
	}
	

	public function isAndroid(){
		if(isset($_SERVER['HTTP_USER_AGENT'])){						
			
			if (strpos($_SERVER['HTTP_USER_AGENT'], 'Android') !== FALSE) { 
				return true;			
			}
			
		}
		return false;
	}
	
	
	public function isMobile(){
		
		if( $this->isIphone() === true ){
			return true;
		}
		
		if( $this->isAndroid() === true ){
			return true;
		}
		
		return false;
		
	}
	
	
}