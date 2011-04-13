<?php

require_once 'Base.php';

/**
 * 
 * Encryption / Decryption Class
 * 
 * This class is used to encrypt or decrypt data
 *  
 * @package Oh
 * @author Craig Hausner 
 * @uses Base
 * 
 */
class Oh_Crypt {
	
	protected static $key = 'mFhfJOQ80oQmotIrZ3B3';
	protected static $cipher = MCRYPT_RIJNDAEL_256;	
	protected static $mode = MCRYPT_MODE_ECB;			
	
	
	public static function getIV(){
		$iv_size = mcrypt_get_iv_size( self::$cipher, self::$mode );
		return mcrypt_create_iv( $iv_size, MCRYPT_RAND );
	}
	
	/**
	* Encrypt data
	* 	
	* @param string $text text to encrypt		
	* @return string encrypted text
	*/
	public static function encrypt($text){
		return base64_encode( mcrypt_encrypt(self::$cipher, self::$key, $text, self::$mode, self::getIV()) );		
	}
	
	
	/**
	* Decrypt data
	* 	
	* @param string $text encrypted text		
	* @return string decrypted text
	*/
	public static function decrypt($text){
		return preg_replace("(\r\n|\n|\r|\t)", "", str_replace("\x0", '',  mcrypt_decrypt(self::$cipher, self::$key, base64_decode( $text ), self::$mode, self::getIV()) ) );
	}
	
}

?>