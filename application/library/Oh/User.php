<?php


class Oh_User {
					
	
	protected $_id;
	protected $_firstName;
	protected $_lastName;
	protected $_username;
	protected $_password;
	protected $_manager;	
	protected $_gdataEncryptUsername = null;
	protected $_gdataEncryptPassword = null;
	
	
	public function __construct(Oh_UserManager $manager){
		$this->_manager = $manager;
	} 
	
									
	public function getId(){
		return $this->_id;
	}
	
	
	public function getUserId(){
		return $this->_id;
	}
	
	
	public function getFirstName(){
		return $this->_firstName;
	}
	
	
	public function setFirstName($firstName){
		$this->_firstName = $firstName;
	}
	
	
	public function getLastName(){
		return $this->_lastName;
	}
	
	
	public function setLastName($lastName){
		$this->_lastName = $lastName;
	}

	
	public function getUsername(){
		return $this->_username;
	}
	
	
	public function setUsername($username){
		
		$username_validator = new Zend_Validate();
		$username_validator->addValidator(new Zend_Validate_StringLength(4, 12))->addValidator(new Zend_Validate_Alnum());
		
		if( $username_validator->isValid( $username ) === true ){
			$this->_username = $username;
			return true;
		}else{
			return false;
		}
		
	}
	
	
	public function setPassword($password){
		
		$password_validator = new Zend_Validate();
		$password_validator->addValidator(new Zend_Validate_StringLength(6, 12))->addValidator(new Zend_Validate_Alnum());
		
		if( $password_validator->isValid( $password ) === true ){
			$this->_password = md5($password);
			return true;
		}else{
			return false;
		}
		
	}
	
	
	public function getPassword(){
		return $this->_password;
	}
	
	
	public function getManager(){
		return $this->_manager;
	}
	
	
	public function getGdataEncryptUsername(){
		return $this->_gdataEncryptUsername;
	}
	
	
	public function getGdataUsername(){		
		return Oh_Crypt::decrypt( $this->getGdataEncryptUsername() );
	}
	
	
	public function setGdataUsername($username){
		if($username != ''){			
			$this->_gdataEncryptUsername = Oh_Crypt::encrypt( $username );
		}	
	}
	
	
	public function getGdataEncryptPassword(){
		return $this->_gdataEncryptPassword;	
	}
	
	
	public function getGdataPassword(){		
		return Oh_Crypt::decrypt( $this->getGdataEncryptPassword() );
	}
	
	
	public function setGdataPassword($password){
		if($password != ''){
			$this->_gdataEncryptPassword = Oh_Crypt::encrypt($password);
		}
	}
	

	public function setFromArray(array $data){
		
		if( isset($data["ID"]) ){
			$this->_id = $data["ID"];
		}
		
		if( isset($data["FIRST_NAME"]) ){
			$this->_firstName = $data["FIRST_NAME"];
		}
		
		if( isset($data["LAST_NAME"]) ){
			$this->_lastName = $data["LAST_NAME"];
		}
		
		if( isset($data["USERNAME"]) ){
			$this->_username = $data["USERNAME"];
		}
		
		if( isset($data["PASSWORD"]) ){
			$this->_password = $data["PASSWORD"];
		}
		
		if( isset($data["GDATA_USER"]) ){
			$this->_gdataEncryptUsername = $data["GDATA_USER"];
		}
		
		if( isset($data["GDATA_PASS"]) ){
			$this->_gdataEncryptPassword = $data["GDATA_PASS"];
		}
		
	}
	
	
	public function toArray(){
		
		return array(
				"FIRST_NAME" => $this->getFirstName(),
				"LAST_NAME" => $this->getLastName(),
				"USERNAME" => $this->getUsername(),
				"PASSWORD" => $this->getPassword(),
				"GDATA_USER" => $this->getGdataEncryptUsername(),
				"GDATA_PASS" => $this->getGdataEncryptPassword()
		);
		
	}
	
	
}



?>
