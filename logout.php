<?php
	// Destroy Sessions
	setcookie ("USERNAME", "");
	setcookie ("PASSWORD", "");	
	
	include_once ("authconfig.php");

   header("Location: index.php?Msj=true");
	
	
?>
