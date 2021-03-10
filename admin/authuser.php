<?php
	include_once ("../auth.php");
	include_once ("../authconfig.php");
	include_once ("../check.php");
	
	if ($check["level"] != 1 and $check["level"] != 9)
	{
		// Feel free to change the error message below. Just make sure you put a "\" before
		// any double quote.
		print "<font face=\"Arial, Helvetica, sans-serif\" size=\"5\" color=\"#FF0000\">";
		print "<b>Illegal Access</b>";
		print "</font><br>";
  		print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"2\" color=\"#000000\">";
		print "<b>You do not have permission to view this page.</b></font>";
		
		exit; // End program execution. This will disable continuation of processing the rest of the page.
	}	
	
	$user = new auth();
	
	$connection = mysql_connect($dbhost, $dbusername, $dbpass);
	$SelectedDB = mysql_select_db($dbname);
	$listteams = mysql_query("SELECT * from authteam");
	
?>
<?php
// Get initial values from superglobal variables
// Let's see if the admin clicked a link to get here
// or was originally here already and just pressed 
// a button or clicked on the User List

if (isset($_POST['action'])) 
{
	$username = $_POST['username'];
	$password = $_POST['password'];
	
	$team = $_POST['team'];
	$level = $_POST['level'];
	$status = $_POST['status'];
	$action = $_POST['action'];
	$msj = $_POST['msj'];
	$act = "";
}
elseif (isset($_GET['act']))
{
	$act = $_GET['act'];
	$action = "";
}
else
{
	$action = "";
	$username = "";
	$password = "";	
	$team = "";
	$level = "";
	$status = "";
	$action = "";
	$act = "";
	$msj = "";
}

$message = "";

// ADD USER
if ($action == "Add") {
	$situation = $user->add_user($username, $password, $team, $level, $status, $msj);
	
	if ($situation == "blank username") {
		$message = "El campo nombre  no pude estar en blanco.";
		$action = "";
	}
	elseif ($situation == "username exists") {
		$message = "El nombre de usuario ya esta en uso. Por favor elije otro.";
		$action = "";
	}
	elseif ($situation == "blank password") {
		$message = "El password no puede estar vacio.";
		$action = "";
	}
	elseif ($situation == "blank level") {
		$message = "El campo nivel no puede estar vacio.";
		$action = "";
	}
	elseif ($situation == 1) {
		$message = "El usuario nuevo se agrego exitosamente.";
	}
	else {
		$message = "";
	}
}

// DELETE USER
if ($action=="Delete") {
	// Delete record in authuser table
	$delete = $user->delete_user($username);
	
	// Delete record in signup table
	$deletesignup =  mysql_query("DELETE FROM signup WHERE uname='$username'");

	if ($delete && $deletesignup) {
		$message = $delete;
	}
	else {
		$username = "";
		$password = "";
		$team = "Ungrouped";
		$level = "";
		$status = "active";
		$msj = "";
		$message = "El usuario ha sido elminado.";
	}
}

// MODIFY USER
if ($action == "Modify") {
	$update = $user->modify_user($username, $password, $team, $level, $status, $msj);

	if ($update==1) {
		$message = "El detalle de usuario se actualizo exitosamente.";
	}
	elseif ($update == "blank level") {
		$message = "El campo nivel no puede estar vacio.";
		$action = "";
	}
	elseif ($update == "sa cannot be inactivated") {
		$message = "Este usuario no puede ser desactivado";
		$action = "";
	}
	elseif ($update == "admin cannot be inactivated") {
		$message = "Este usuario no puede ser desactivado";
		$action = "";
	}
	else {
		$message = "";
	}
}

// EDIT USER (accessed from clicking on username links)
if ($act == "Edit") 
{
    $username = $_GET['username'];
	$listusers = mysql_query("SELECT * from authuser where uname='$username'");
	$rows = mysql_fetch_array($listusers);
	$username = $rows["uname"];
	$password = "";
	$team = $rows["team"];
	$level = $rows["level"];
	$status = $rows["status"];
	$msj = $rows["msj"];

	$message = "Modificar detalles de usuario.";
}

// CLEAR FIELDS
if ($action == "Add New") {
	$username = "";
	$password = "";
	$team = "Ungrouped";
	$level = "";
	$msj = "";
	$status = "active";
	$message = "Detalle de entrada para nuevo usuario.";
}

?>

<html>
<head>
<title>vAuthenticate Administrative Interface</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p align='center'><font face="Arial, Helvetica, sans-serif" size="5"><b>Administracion - Usuarios</b></font></p>
<table width="75%" align='center' border="1" cellspacing="0" cellpadding="0" bordercolor="#000000">
  <tr bgcolor='#CCCCCC'> 
    <td width="20%" height="16"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Administrador</font></b></td>
    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="index.php">Home</a></font></div>
    </td>

    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authgroup.php">Grupos</a></font></div>
    </td>

    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="<?php echo $logout; ?>">Salir</a></font></div>
    </td>
  </tr>
</table><br>&nbsp;


<table width="95%" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr valign="top"> 
    <td width="50%"> 
      
	  <form name="AddUser" method="Post" action="authuser.php">
	    <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
          <tr bgcolor='#CCCCCC'> 
            <td colspan="2"> 
              <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2" ><b>DETALLES 
                de USUARIO</b></font></div>
            </td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Nombre</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <?php   
			  	if (($action == "Modify") || ($action=="Add") || ($act=="Edit")) {
					print "<input type=\"hidden\" name=\"username\" value=\"$username\">"; 
					print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#006666\" size=\"2\">$username</font>";
				}
				else {	
					print "<input type=\"text\" name=\"username\" size=\"15\" maxlength=\"15\" value=\"$username\">"; 
				}
				
			  ?>
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Password</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <?php print "<input type=\"password\" name=\"password\" size=\"20\" maxlength=\"15\" value=\"$password\">"; ?>
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><font face="Verdana, Arial, Helvetica, sans-serif" size="1">&nbsp;</font></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="1" color="#000099">&nbsp;&nbsp;Deja 
              en blanco el campo password si deseas mantener el password antiguo. 
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Grupo</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <select name="team">
                <?php
			  	// DISPLAY TEAMS
			  	$row = mysql_fetch_array($listteams);
			  	while ($row) {
					$teamlist = $row["teamname"];
					
					if ($team == $teamlist) {
						print "<option value=\"$teamlist\" SELECTED>" . $row["teamname"] . "</option>";
					}
					else {
						print "<option value=\"$teamlist\">" . $row["teamname"] . "</option>";
					}
					$row = mysql_fetch_array($listteams);
				}
			  ?>
              </select>
              <a href="authgroup.php">Agregar</a></font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Nivel</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <?php print "<input type=\"text\" name=\"level\" size=\"4\" maxlength=\"4\" value=\"$level\">"; ?>
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Status</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <select name="status">
                <?php
			  	// ACTIVE / INACTIVE
				if ($status == "inactive") {
					print "<option value=\"active\">Active</option>";
                	print "<option value=\"inactive\" selected>Inactivo</option>";
				}
				else {
					print "<option value=\"active\" selected>Activo</option>";
                	print "<option value=\"inactive\">Inactivo</option>";
				}
              
			  ?>
              </select>
              </font></td>
          </tr>
          
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Mensajes</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp;
            <?php
             
              echo "<select name='msj'>";
              echo "<option value='Si'>Si</option>";
              echo "<option value='No'>No</option>";
              echo "<option selected value='$msj'>$msj</option>";
              echo "</select> &nbsp; ";
              
			  ?>
            </td>
          </tr>
          
          
          
          
          
          <tr valign="middle"> 
            <td colspan="2"> 
              <div align="center"><font size="2"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif"> 
                <?php
					
				if (($action=="Add") || ($action == "Modify") || ($act=="Edit")) {
					print "<input type=\"submit\" name=\"action\" value=\"New User\"> ";
					print "<input type=\"submit\" name=\"action\" value=\"Modify\"> ";
					print "<input type=\"submit\" name=\"action\" value=\"Delete\"> ";
				}
				else {
					print "<input type=\"submit\" name=\"action\" value=\"Add\"> ";
                }
				
				?>
                <input type="reset" name="Reset" value="Limpiar">
                </font></font></font></font></div>
            </td>
          </tr>
        </table>
	  </form>
	  

      <p>&nbsp;</p>
      <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
        <tr> 
          <td bgcolor="#990000"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFCC">Mensaje:</font></b></td>
        </tr>
        <tr> 
          <td><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#0000FF">
		  <?php
		  	if ($message) {
			 	print $message;
		  	}
			else {
				print "<BR>&nbsp;";
			}
		  ?>
		  </font></td>
        </tr>
      </table>
      <p>&nbsp;</p>
      </td>
    <td width="50%"> 
      
	  
	  <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
        <tr bgcolor='#CCCCCC'> 
          <td colspan="5"> 
            <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>LISTA 
              de USUARIOS</b></font></div>
          </td>
        </tr>
        <tr> 
          <td width="20%"> 
            <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Nombre</b></font></div>
          </td>
          <td width="25%"> 
            <div align="center"><font size="1"><b><font face="Verdana, Arial, Helvetica, sans-serif">Grupo</font></b></font></div>
          </td>
          <td width="15%"> 
            <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Status</b></font></div>
          </td>
          <td width="30%"> 
            <div align="center"><font size="1"><b><font face="Verdana, Arial, Helvetica, sans-serif">Ultima 
              sesion </font></b></font></div>
          </td>
          <td width="10%"> 
            <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Cont</b></font></div>
          </td>
        </tr>

<?php
	// Fetch rows from AuthUser table and display ALL users
	// OLD CODE - DO NOT REMOVE
	// $result = mysql_db_query($dbname, "SELECT * FROM authuser ORDER BY id");
	
	// REVISED CODE
	$result = mysql_query("SELECT * FROM authuser ORDER BY id");
	
	$row = mysql_fetch_array($result);
	while ($row) {  		
		print "<tr>"; 
        print "  <td width=\"20%\">";
        print "    <div align=\"left\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">";
		print "		<a href=\"authuser.php?act=Edit&username=".$row['uname']."\">";
		print 		$row['uname'];
		print "		</a>";
		print "	   </font></div>";
        print "  </td>";
        print "  <td width=\"25%\">";
        print "    <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">".$row['team']."</font></div>";
        print "  </td>";
        print "  <td width=\"15%\">";
        print "    <div align=\"center\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".($row['status'])."</font></div>";
        print "  </td>";
        print "  <td width=\"30%\">";
        print "    <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">".$row['lastlogin']."</font></div>";
        print "  </td>";
        print "  <td width=\"10%\">";
        print "    <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".($row['logincount'])."</font></div>";
        print "  </td>";
        print "</tr>";
		
		$row = mysql_fetch_array($result);
	}
?>
     
	  </table>
	  
      
    </td>
  </tr>
</table>

</body>
</html>
