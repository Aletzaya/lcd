<?php
        // Start Code

	// Use Sessions
	// NOTE: This will store the username and password entered by the user to the cookie
	// variables USERNAME and PASSWORD respectively even if the combination is correct or
	// not. Be sure to authenticate every page that you want to be secured and pass as 
	// parameters the variables USERNAME and PASSWORD.
	
	setcookie ("USERNAME", $_POST['username']);
	setcookie ("PASSWORD", $_POST['password']);
 
        // Change the path to auth.php and authconfig.php if you moved
        // vAuthenticate.php from its original directory.
  	include_once ("auth.php");
	include_once ("authconfig.php");
 
        $username =  $_POST['username'];
        $password =  $_POST['password'];

	$Auth = new auth();
	$detail = $Auth->authenticate($username, $password);


	if ($detail==0){

	?>

	<HEAD>
		<SCRIPT language="JavaScript1.1">
		<!--
			location.replace("<?php echo $failure; ?>");
		//-->
		</SCRIPT>
	  </HEAD>

	<?php
	}elseif ($detail['level'] == 1) {
	?>
	  <HEAD>
		<SCRIPT language="JavaScript1.1">

		<!--
			location.replace("<?php echo $admin; ?>");
		//-->
		
		</SCRIPT>
	  </HEAD>

	<?php
	}elseif($detail == 2) {
	?>
	
	<HEAD>
		<SCRIPT language="JavaScript1.1">
		<!--
			location.replace("<?php echo $changepassword; ?>");
		//-->
		</SCRIPT>
	  </HEAD>

	<?php
	}else{
	?>
	
	<HEAD>
		<SCRIPT language="JavaScript1.1">
		<!--
			location.replace("<?php echo $success; ?>");
		//-->
		</SCRIPT>
	  </HEAD>

	<?php
   }


?>
