<?php

require_once 'Oh/Crypt.php';

fwrite(STDOUT, "Please enter the text you would like to decrypt\n");

$text = fgets(STDIN);

fwrite(STDOUT, Oh_Crypt::decrypt($text) ."\n" );

exit(0); 

?>