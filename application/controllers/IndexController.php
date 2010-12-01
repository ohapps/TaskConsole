<?php


/**
 * Index Controller 
 * 
 * The default controller class
 * 
 * @package TaskConsole
 * @author Craig Hausner 
 * @uses Zend_Loader 
 */
class IndexController extends Zend_Controller_Action {

	
	public function init(){

		if( $this->_helper->mobile->isIphone() == true ){
			$this->_forward('index','mobile');
		}
		
	}
	
	
	/**
	 * The default action - show the home page
	 */
    public function indexAction() 
    {
     	
    }
	
	
}
