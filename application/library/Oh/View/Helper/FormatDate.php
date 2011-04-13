<?php

class Oh_View_Helper_FormatDate{

	public function formatDate($date,$format='Y-m-d'){
		return date($format,strtotime($date));
	}
	
}