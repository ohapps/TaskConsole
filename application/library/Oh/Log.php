<?php

require_once 'Zend/Log.php';
require_once 'Zend/Log/Writer/Db.php';

class Oh_Log extends Oh_Base {

	protected $logger;

	function __construct(){
		parent::__construct();
		$writer = new Zend_Log_Writer_Db(
			$this->conn,
			'log',
			array(
				'LVL' => 'priority',
				'MSG' => 'message',
				'LOG_DATE' => 'timestamp'
			)
		);
		$this->logger = new Zend_Log($writer);
	}


	function logError( $msg ){
		$this->logger->log( $msg, Zend_Log::ERR);
	}


	function logDebug( $msg ){
		$this->logger->log( $msg, Zend_Log::DEBUG);
	}


	function logInfo( $msg ){
		$this->logger->log( $msg, Zend_Log::INFO);
	}

}

?>
