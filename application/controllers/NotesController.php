<?php


/**
 * 
 * Notes Controller
 * 
 * This is the controller for the notes section of the application.
 *  
 * @package TaskConsole
 * @author Craig Hausner 
 * @uses Zend_Loader
 * 
 */
class NotesController extends Zend_Controller_Action 
{
	
	
	public function init(){

		if( $this->_helper->mobile->isIphone() == true ){
			$this->_forward('index','mobile');
		}
		
	}
	
	
	/***************************************
	* LOAD VIEW SCRIPTS
	****************************************/	

	
	/**
	* default action that directs to the main project screen
	*/	 
	public function indexAction() 
    {    	
		
    	if( $this->_hasParam('id') ){   
     	 	$this->view->note_id = $this->_getParam('id');
    	}else{
    		$this->view->note_id = "";
    	}  
		
    }

    
	/**
	* view note
	*/	 	
	public function viewAction(){
		
		$this->_helper->layout()->disableLayout();
				
		$user = Zend_Registry::get('user');
		$config = $this->getInvokeArg('bootstrap')->getOption('app');	

		$note = Doctrine_Core::getTable('Console_Note')->find($this->_getParam('id'));
		
		if( $note == false ){
			throw new Exception('invalid note id');
		}
		
		if( $note->Topic->isUserTopic($user->getUserId()) == false ){
			throw new Exception('invalid note id');
		}
		
		$today = new Zend_Date();
		$note->markViewed($today->toString($config['date_format']));
		
		$this->view->note = $note;		
				
	}


	/***************************************
	* MANAGE TOPICS
	****************************************/

	
	/**
	* default action that directs to the main project screen
	*/	 
	public function saveTopicAction(){
		
    	$user = Zend_Registry::get('user');    	    	    		
    	
    	try{
    		
	    	if( $this->_getParam('ID') == "" ){    		    	
	    		$topic = new Console_Topic();
	    		$topic->USER_ID = $user->getUserId();
	    	}else{	    		
	    		
	    		$topic = Doctrine_Core::getTable('Console_Topic')->find($this->_getParam('ID'));
	    		
	    		if( $topic == false ){
	    			throw new Exception('invalid topic id when saving topic');
	    		}
	    		
	    		if( $topic->isUserTopic($user->getUserId()) == false ){
	    			throw new Exception('topic id does not belong to current user when saving topic');
	    		}	    		
	    		
	    	}
	    	
	    	$topic->DESCRIPTION = $this->_getParam('DESCRIPTION');	    	
	    	$topic->save();
	    	
	    	$this->_helper->json->sendJson( array( "success" => true, "grid_id" => $this->_getParam('GRID_ID'), "id" => $topic->ID ) );	    		    	
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );	
    	}    	    	    	
		
	}

	
	/**
	* delete a topic
	*/	 
	public function deleteTopicAction(){		
    	    	
    	$user = Zend_Registry::get('user');    	
    	
    	try{

    		$topic = Doctrine_Core::getTable('Console_Topic')->find($this->_getParam('ID'));
    		
    		if( $topic == false ){
    			throw new Exception('invalid topic id');
    		}
    		
    		if( $topic->isUserTopic($user->getUserId()) == false ){
    			throw new Exception('topic id does not belong to current user when deleting topic');
    		}
    			
    		$topic->delete();
    		
    		$this->_helper->json->sendJson( array( "success" => true ) );
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );
    	}
								
	}	


	/***************************************
	* MANAGE NOTES
	****************************************/	

	
	/**
	* create or update a note
	*/	 	
	public function saveNoteAction(){
								
		$config = $this->getInvokeArg('bootstrap')->getOption('app');
		$user = Zend_Registry::get('user');
		$today = new Zend_Date();			
				
		try{
			
			if( $this->_hasParam('DATA') == false ){
				throw new Exception('missing data parameter when saving note');
			}
			
			$data = $this->_getParam('DATA');		
			
			if( isset($data["TOPIC_ID"]) == false ){
				throw new Exception('missing TOPIC_ID parameter when saving note');
			}

			$topic = Doctrine_Core::getTable('Console_Topic')->find($data["TOPIC_ID"]);
			
			if( $topic == false ){
    			throw new Exception('invalid topic id');
    		}
    		
    		if( $topic->isUserTopic($user->getUserId()) == false ){
    			throw new Exception('topic id does not belong to current user when deleting topic');
    		}
			
			if( isset($data["ID"]) == false ){
				throw new Exception('missing ID parameter when saving note');
			}
						
			$id = $data["ID"];	
			
	    	if( $id == "" ){    		    	
	    		$note = new Console_Note();
	    	}else{
	    		
	    		$note = Doctrine_Core::getTable('Console_Note')->find($id);
	    		
	    		if( $note == false ){
	    			throw new Exception('invalid id when saving note');
	    		}
	    			    			    		
	    	}

	    	$note->fromArray($data);	    	
			$note->LAST_UPDATE = $today->toString($config['date_format']);			
			$note->LAST_VIEWED = $today->toString($config['date_format']);
	    	$note->save();
	    	
	    	$this->_helper->json->sendJson( array( "success" => true, "id" => $note->ID ) );
    	}catch( Exception $e ){
    		$this->_helper->json->sendJson( array( "success" => false ) );	
    	}    	    	   
		
	}	

	
	/**
	* delete a note
	*/	 
	public function deleteAction(){
			
		$this->_helper->layout()->disableLayout();
		$user = Zend_Registry::get('user');	
		
		if( $this->_hasParam('id') == false ){
			throw new Exception('missing id parameter when deleting note');
		}
		
		$note = Doctrine_Core::getTable('Console_Note')->find($this->_getParam('id'));
		
		if( $note == false ){
			throw new Exception('invalid note id when deleting note');
		}
		
		if( $note->Topic->isUserTopic($user->getUserId()) == false ){
			throw new Exception('note does not belong to current user when deleting note');	
		}
		
		$note->delete();
										
	}

	
	/***************************************
	* JSON QUERIES
	****************************************/		

	
	/**
	* return json response to load list tree of topics and notes
	*/	 
	public function loadTopicsAction(){
		
		$tree = Array();
    	$user = Zend_Registry::get('user');    	
    	$expand = false;
    	
    	if( $this->_getParam('node') != "" && $this->_getParam('node') != "root" ){
    		$expand = true;	
    		$topics = Doctrine_Core::getTable('Console_Topic')->getByUserId( $user->getUserId(), $this->_getParam('node') );    		    		    	    		
    	}else{
    		$topics = Doctrine_Core::getTable('Console_Topic')->getByUserId( $user->getUserId() );
    	}    	    	    	    	
    	
    	foreach( $topics as $topic ){    		    		
    		
    		$children = array();
    		
    		foreach( $topic->Notes as $note ){
				$children[] = array( 
					"id" => "note_" . $note->ID, 
					"text" => $note->DESCRIPTION, 
					"leaf" => true, 
					"href" => "javascript:loadNote(". $note->ID .")"  
				);    			
    		}
    		
    		if ( count($children) > 0 ){
    			$tree[] = array( 
    				"id" => "topic_" . $topic->ID, 
    				"text" => $topic->DESCRIPTION, 
    				"cls" => "folder", 
    				"expanded" => $expand, 
    				"children" => $children 
    			);    	
    		}
    		
    	}    	
    	    	    
    	$this->_helper->json->sendJson( $tree );
		
	}	

	
	/**
	* return json response of all topics for the current user
	*/	 	
	public function getTopicsAction(){
				    	
    	$user = Zend_Registry::get('user');
    	$topics = Doctrine_Core::getTable('Console_Topic')->getByUserId( $user->getUserId() );
    	    	    					
		$this->_helper->json->sendJson( array("data" => $topics->toArray() ) );
		
	}

	
	/**
	* return json response of a note
	*/	 
	public function loadNoteAction(){
				
		$data = array();
		$user = Zend_Registry::get('user');
		
		try{
			
			if( $this->_hasParam('id') == false ){
				throw new Exception('missing note id parameter when loading note');
			}
									
			$note = Doctrine_Core::getTable('Console_Note')->find($this->_getParam('id'));

			if( $note == false ){
				throw new Exception('invalid note id when loading note');
			}
			
			if( $note->Topic->isUserTopic($user->getUserId()) == false ){
				throw new Exception('note does not belong to current user when loading note');	
			}
			
			$data["DATA[ID]"] = $note->ID;
			$data["DATA[DESCRIPTION]"] = $note->DESCRIPTION;
			$data["DATA[TOPIC_ID]"] = $note->TOPIC_ID;
			$data["DATA[CONTENTS]"] = $note->CONTENTS;
									
			$this->_helper->json->sendJson( array( "success" => true, "data" => $data ) );
		}catch(Exception $e){
			$this->_helper->json->sendJson( array( "success" => false ) );
		}
		
	}

	
	/**
	* return json response of recently viewed notes
	*/	 
	public function recentlyViewedAction(){
				    	
		$user = Zend_Registry::get('user');
		$config = $this->getInvokeArg('bootstrap')->getOption('app');
		$data = array();

		$notes = Doctrine_Core::getTable('Console_Note')->getRecentlyViewed( $user->getUserId(), $config['recent_note_days'] );				
		
		foreach( $notes as $note ){
			$data[] = array(
				"ID" => $note->ID,
				"TOPIC_ID" => $note->Topic->ID,
				"CONTENTS" => $note->CONTENTS,
				"DESCRIPTION" => $note->DESCRIPTION,
				"LAST_VIEWED" => $this->_helper->date->format( $note->LAST_VIEWED, $config['date_format'] ),
				"TOPIC" => $note->Topic->DESCRIPTION
			);
		}
		
		$this->_helper->json->sendJson( array( "data" => $data, "days" => $config['recent_note_days'] ) );
		
	}
	
	
}

?>
