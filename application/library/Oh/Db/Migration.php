<?php

require_once 'Zend/Config/Ini.php';
require_once 'Zend/Db.php';
require_once 'Oh/Crypt.php';

class Oh_Db_Migration {
	
	
	private $config;	
	private $db;
	private $scripts = array();
	private $success = 0;
	private $error = 0;
	private $errors = array();
	private $current_migration;
	private $script_variables = array( "::semi::" => ";" );	 
	
	const MIGRATION_DIR = "/migrations";
	const MIGRATION_EXT = ".sql";
	const MIGRATION_TABLE = "SCHEMA_INFO";
	const MIGRATION_COL = "CURRENT_MIGRATION";	
	
	
	public function __construct( $env, $rootDir ){
		
		define('ROOT_DIR',$rootDir);
		
		$this->config = new Zend_Config_Ini( ROOT_DIR . '/application/config/app.ini', $env );
		
		// DECRYPT DATABASE PASSWORD				
		$db_config = $this->config->db->config->toArray();
		$db_config['password'] = Oh_Crypt::decrypt( $db_config['password'] );

		// SETUP DATABASE CONNECTION
		$this->db = Zend_Db::factory($this->config->db->adapter, $db_config);
				
		$this->getCurrentMigration();		
				
	}
	
	
	public function getCurrentMigration(){
		
		$tables = $this->db->listTables();
		
		if(in_array(self::MIGRATION_TABLE,$tables)){
			$this->current_migration = $this->db->fetchOne("SELECT max(". self::MIGRATION_COL .") FROM ". self::MIGRATION_TABLE );
			if( $this->current_migration == "" ){
				$this->db->insert( self::MIGRATION_TABLE, array( self::MIGRATION_COL => 0 ) );
				$this->current_migration = 0;
			}
		}else{
			$this->db->query("create table ". self::MIGRATION_TABLE ." ( ". self::MIGRATION_COL ." INTEGER )");
			$this->db->insert( self::MIGRATION_TABLE, array( self::MIGRATION_COL => 0 ) );
			$this->current_migration = 0;
		}				
		
	}
	
	
	public function updateCurrentMigration($number){
		$this->db->update( self::MIGRATION_TABLE, array( self::MIGRATION_COL => $number ) );
		$this->current_migration = $number;
	}
	
	
	public function migrate(){
		
		$this->scanScripts();
		
		try{					
			
			foreach( $this->scripts as $script ){
									
				$migration_number = substr( $script, 0, 3 );
												
				if( $migration_number > $this->current_migration ){
														
					$sql = rtrim( preg_replace("(\r\n|\n|\r|\t)", " ",  file_get_contents (  ROOT_DIR . self::MIGRATION_DIR . "/" . $script ) ), " ");
															
					$statements = explode(";",$sql);
					
					foreach( $statements as $statement ){
					
						if( $statement != "" ){
							$this->db->query( strtr( $statement, $this->script_variables ) );
						}
												
					}																					
						
					$this->success++;
					 
					$this->updateCurrentMigration($migration_number);
				
				}
																		
			}											
			
		}catch(Exception $err){							
				
			$this->error++;
			$this->errors[] = $err->getMessage();
								
		}
		
		
	}
	
	
	public function scanScripts(){
		
		$files = array_diff( scandir( ROOT_DIR . self::MIGRATION_DIR ), array('.','..'));
		
		foreach( $files as $file ){
			if( is_file( ROOT_DIR . self::MIGRATION_DIR . "/" . $file ) && substr( $file , -4) == self::MIGRATION_EXT ){
				$this->scripts[] = $file;		
			}
		}				
		
	}
	
	
	public function getScripts(){
		return $this->scripts;
	}
	
	
	public function getSuccess(){
		return $this->success;
	}
	
	
	public function getErrors(){
		return $this->error;
	}
	
	public function getErrorMessages(){
		return $this->errors;
	}
	
	
}


?>