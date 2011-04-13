<?php

class Oh_Action_Helper_Date extends Zend_Controller_Action_Helper_Abstract {
	
	
	public function format($date,$format='Y-m-d'){		
		if( $date != null && $date != "" ){
			return date($format,strtotime($date));
		}
		return null;
	}
	
	
}