<?php

require_once 'Oh/Crypt.php';

fwrite(STDOUT, "Please enter the text you would like to encrypt\n");

$text = fgets(STDIN);

fwrite(STDOUT, Oh_Crypt::encrypt($text) ."\n" );

exit(0); 

?>