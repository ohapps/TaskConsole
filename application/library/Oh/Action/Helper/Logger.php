<?php

class Oh_Action_Helper_Logger extends Zend_Controller_Action_Helper_Abstract {
	
	
	public function log(){		
		
		if( $this->getActionController()->getInvokeArg('bootstrap')->hasResource('log') ){
			$logger = $this->getActionController()->getInvokeArg('bootstrap')->getResource('log');
		}else{
			throw new Exception('missing bootstrap resource log');	
		}
		
		return $logger;
		
	}
	
	
}