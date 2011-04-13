<?php

require_once 'Oh/Db/Migration.php';

fwrite(STDOUT, "Starting Database Migration\n");

$env = getenv('APP_ENV') ? getenv('APP_ENV') : 'dev' ;

fwrite(STDOUT, "Environment: {$env}\n");

$dir = getenv('PWD');

fwrite(STDOUT, "Root Dir: {$dir}\n");

$migration = new Oh_Db_Migration( $env, $dir );

$migration->migrate();

/*
fwrite(STDOUT, "The following scripts are available\n");

$scripts = $migration->getScripts();

foreach( $scripts as $script ){
	fwrite(STDOUT, "{$script}\n");
}
*/

fwrite(STDOUT, $migration->getSuccess() . " migrations completed\n");

if( $migration->getErrors() > 0 ){
	fwrite(STDOUT, "The following migration failed:\n");
	$errors = $migration->getErrorMessages();
	foreach( $errors as $error ){
		fwrite(STDOUT, "{$error}\n\n");
	}
}

exit(0);

?>