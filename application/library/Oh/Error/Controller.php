<?php

require_once 'Zend/Controller/Action.php' ;

class Oh_Error_Controller extends Zend_Controller_Action {
	
	public function errorAction () {
		
		$this->_helper->layout->disableLayout();
		$this->_helper->viewRenderer->setNoRender();
		
		$errors = $this->_getParam ('error_handler') ;						
		
		$logger = Zend_Registry::get('logger');
		$logger->logError( $errors->exception->getMessage() . $errors->exception->getTraceAsString() );
		
		$view = new Zend_View();
		$view->setBasePath( dirname(__FILE__) );
		
		// SET EXCEPTIONS		
		if ( ini_get('display_errors') == 1 ){
			$view->error = $errors->exception->getMessage();
			$view->trace = $errors->exception->getTraceAsString();
		}				
		
		echo $view->render('error/error.phtml');		
				
	}
	
}

?>