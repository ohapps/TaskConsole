<?php

class Oh_View_Helper_BaseUrl{

	public function baseUrl(){
		
		$controller = Zend_Controller_Front::getInstance();
		return $controller->getBaseUrl();
		
	}
	
}

?>