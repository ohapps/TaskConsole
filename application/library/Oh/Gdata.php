<?php

require_once 'Base.php';
require_once 'Crypt.php';
require_once 'Zend/Gdata/Calendar.php';
require_once 'Zend/Gdata/ClientLogin.php';

/**
 * 
 * Google Data Class
 * 
 * This class is used to interface with the Google Data API
 *  
 * @package Oh
 * @author Craig Hausner 
 * @uses Base
 * @uses Crypt
 * @uses Zend_Gdata_Calendar
 * @uses Zend_Gdata_ClientLogin
 * 
 */
class Oh_Gdata extends Oh_Base {
	
	protected $cal_service;
	protected $connected = false;

	
	function __construct( $user_id ){

		parent::__construct();
				
		try{

			$crypt = new Oh_Crypt();		
			$stmt = $this->conn->query("select gdata_user, gdata_pass from oha_user where id = '". $user_id ."'");
			$user_rec = $stmt->fetchObject();
			if( $user_rec->gdata_user != "" && $user_rec->gdata_pass != "" ){								 				
				$client = Zend_Gdata_ClientLogin::getHttpClient( $crypt->decrypt( $user_rec->gdata_user ), $crypt->decrypt( $user_rec->gdata_pass ), Zend_Gdata_Calendar::AUTH_SERVICE_NAME);
				$this->cal_service = new Zend_Gdata_Calendar($client);			
				$this->connected = true;
			}else{
				$this->connected = false;
			}
			
		}catch(Exception $err){
			$this->connected = false;
		}		
				
	}

	
	/**
	* Checks if the connection to Google was successful
	* 
	* @return boolean returns true if successful and false if not successful
	*/
	public function isConnected(){
		return $this->connected;
	}
	
	
	/**
	* Add event to user's Google Calendar
	* 	
	* @param string $title title of event	
	* @param date $startDate start date for event ( format: YYYY-MM-DD )
	* @param date $endDate end date for event ( format: YYYY-MM-DD )
	* @param string $where location of event ( defaults to null )
	* @param string $content details of event ( defaults to null )
	* @return string|boolean returns the event id if successful or false if not successful
	*/
	public function createGCalEvent( $title, $startDate, $endDate, $where = null, $content = null ){
		
		try{
			
			if( $this->isConnected() == true ){
				
				$event= $this->cal_service->newEventEntry();
				$event->title = $this->cal_service->newTitle($title);		
				$when = $this->cal_service->newWhen();
				$when->startTime = "{$startDate}";
				$when->endTime = "{$endDate}";
				$event->when = array($when);
				
				if( $where != null ){
					$event->where = array($this->cal_service->newWhere($where));
				}
				
				if( $content != null ){
					$event->content = $this->cal_service->newContent($content);
				}
				
				$newEvent = $this->cal_service->insertEvent($event);
				
				return $newEvent->id;
				
			}else{
				return false;
			}
		
		}catch(Exception $err){			
			return false;			
		}		
		
	}
	
	
	/**
	* Update event for user's Google Calendar
	* 
	* @param string $id event id	
	* @param string $title title of event	
	* @param date $startDate start date for event ( format: YYYY-MM-DD )
	* @param date $endDate end date for event ( format: YYYY-MM-DD )
	* @param string $where location of event ( defaults to null )
	* @param string $content details of event ( defaults to null )
	* @return boolean returns true if successful or false if not successful
	*/
	public function updateGCalEvent( $id, $title, $startDate, $endDate, $where = null, $content = null ){
		
		try{		
			
			if( $this->isConnected() == true ){
			
				$event = $this->cal_service->getCalendarEventEntry($id);
				$event->title = $this->cal_service->newTitle($title);
				$when = $this->cal_service->newWhen();
				$when->startTime = "{$startDate}";
				$when->endTime = "{$endDate}";
				$event->when = array($when);
				
				if( $where != null ){
					$event->where = array($this->cal_service->newWhere($where));
				}
				
				if( $content != null ){
					$event->content = $this->cal_service->newContent($content);
				}
				
				$event->save();
						
				return true;
			
			}else{
				return false;
			}
			
		}catch(Exception $err){
			return false;
		}		
		
	}		
	
	
	/**
	* Deletes event from user's Google Calendar
	* 
	* @param string $id event id		
	* @return boolean returns true if successful or false if not successful
	*/
	public function deleteGCalEvent($id){
		
		try{
		
			if( $this->isConnected() == true ){
			
				$event = $this->cal_service->getCalendarEventEntry($id);
				$event->delete();	
				
				return true;
			
			}else{
				return false;
			}
			
		}catch(Exception $err){
			return false;
		}		
			
	}
	
	
}

?>