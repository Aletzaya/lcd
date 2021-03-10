<?php
/*===========*/
?>
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
		print "<b>No tiene permiso para ver esta.</b></font>";
		
		exit; // Termina la ejecucion del programa. detiene la carga del resto d ela pagina.
	}		
	$group = new auth();

	$connection = mysql_connect($dbhost, $dbusername, $dbpass);
	$SelectedDB = mysql_select_db($dbname);
	$listusers = mysql_query("SELECT * from authuser");

?>
<?php
// Check if we have instantiated $action and $act variable
// If yes, get the value from previous posting
// If not, set values to null or ""
 
if (isset($_POST['action'])) 
{
	$action = $_POST['action'];
	$act = "";
	$teamname = $_POST['teamname'];
	$teamlead = $_POST['teamlead'];
	$status = $_POST['status'];
}
elseif (isset($_GET['act']))
{
	$act = $_GET['act'];
	$action = "";
}
else
{
	$action = "";
	$act = "";
	$teamname = "";
	$teamlead = "";
	$status = "";
}

$message = "";

// ADD GROUP
if ($action == "Add") {
	$situation = $group->add_team($teamname, $teamlead, $status);
	
	if ($situation == "blank team name") {
		$message = "El campo nombre  no puede estar vacio.";
		$action = "";
	}
	elseif ($situation == "group exists") {
		$message = "El nombre del grupo ya existe. Por favor elige otro.";
		$action = "";
	}
	elseif ($situation == 1) {
		$message = "Se agrego un nuevo grupo exitosamente.";
	}
	else {
		$message = "";
	}
}

// DELETE GROUP
if ($action=="Delete") {
	$delete = $group->delete_team($teamname);
	
	if ($delete) {
		$message = $delete;
		$action = "";
	}
	else {
		$teamname = "";
		$teamlead = "sa";
		$status = "active";
		$message = "El grupo ha sido eliminado.<br>Todos los usuarios relacionados se desagruparon";
	}
}

// MODIFY TEAM
if ($action == "Modify") {
	$update = $group->modify_team($teamname, $teamlead, $status);

	if ($update==1) {
		$message = "Se actualizaron exitosamente los detalles de Grupo.";
	}
	elseif ($update == "el grupo Admin no puede ser desactivado.") {
		$message = $update;
		$action = "";
	}
	elseif ($update == "los usuarios desagrupados no pueden ser desactivados.") {
		$message = $update;
		$action = "";
	}
	elseif ($update == "La funcion del grupo no puede estar en blanco.") {
		$message = $update;
		$action = "";
	}
	else {
		$message = "";
	}
}

// EDIT TEAM (accessed from clicking on username links)
if ($act == "Edit") {
    $teamname = $_GET['teamname'];
    $teamlead = $_GET['teamlead'];
    $status = $_GET['status'];
    $message = "Detalles de modificacion de grupo";
}

// CLEAR FIELDS
if ($action == "Add New") {
	$teamname = "";
	$teamlead = "sa";
	$status = "active";
	$message = "Nueva entrada de detalles de grupo.";
}

?>
<html>
<head>
<title>Interface Adminsitrativa</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body bgcolor="#FFFFFF" text="#000000">
<p><font face="Arial, Helvetica, sans-serif" size="5"><b> Administracion - Grupos</b></font></p>
<table width="75%" border="1" cellspacing="0" cellpadding="0" bordercolor="#000000">
  <tr bgcolor='#CCCCCC'> 
    <td width="20%" height="16"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Administrador</font></b></td>
    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="index.php">Home</a></font></div>
    </td>

    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="authuser.php">Usuarios</a></font></div>
    </td>

    <td width="16%" height="16"> 
      <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><a href="<?php echo $logout; ?>">Salir</a></font></div>
    </td>
  </tr>
</table><br>&nbsp;
<table width="95%" border="0" cellspacing="0" cellpadding="0" align="left">
  <tr valign="top"> 
    <td width="50%"> 
      
	  <form name="AddTeam" method="Post" action="authgroup.php">
	    <table width="95%" border="1" cellspacing="0" cellpadding="0" align="center" bordercolor="#000000">
          <tr bgcolor="#CCCCCC"> 
            <td colspan="2"> 
              <div align="center"><font face="Verdana, Arial, Helvetica, sans-serif" size="2"><b>DETALLES 
                de GRUPO</b></font></div>
            </td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Nombre</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <?php   
			  	if (($action == "Modify") || ($action=="Add") || ($act=="Edit")) {
					print "<input type=\"hidden\" name=\"teamname\" value=\"$teamname\">"; 
					print "<font face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"#006666\" size=\"2\">$teamname</font>";
				}
				else {	
					print "<input type=\"text\" name=\"teamname\" size=\"15\" maxlength=\"15\" value=\"$teamname\">"; 
				}
				
			  ?>
              </font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font size="2" face="Verdana, Arial, Helvetica, sans-serif">Funcion</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <select name="teamlead">
                <?php
			  	// DISPLAY MEMBERS
			  	$row = mysql_fetch_array($listusers);
			  	while ($row) {
					$memberlist = $row["uname"];
					
					if ($teamlead == $memberlist) {
						print "<option value=\"$memberlist\" SELECTED>" . $row["uname"] . "</option>";
					}
					else {
						print "<option value=\"$memberlist\">" . $row["uname"] . "</option>";
					}
					$row = mysql_fetch_array($listusers);
				}
			  ?>
              </select>
              <a href="authuser.php">Agregar</a></font></td>
          </tr>
          <tr valign="middle"> 
            <td width="27%"><b><font face="Verdana, Arial, Helvetica, sans-serif" size="2">Status</font></b></td>
            <td width="73%"><font face="Verdana, Arial, Helvetica, sans-serif" size="2">&nbsp; 
              <select name="status">
                <?php
			  	// ACTIVE / INACTIVE
				if ($status == "inactive") {
					print "<option value=\"active\">Activo</option>";
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
          <tr bgcolor="#CCCCCC" valign="middle"> 
            <td colspan="2"> 
              <div align="center"><font size="2"><font size="2"><font size="2"><font face="Verdana, Arial, Helvetica, sans-serif"> 
                <?php
					
				if (($action=="Add") || ($action == "Modify") || ($act=="Edit")) {
					print "<input type=\"submit\" name=\"action\" value=\"Add New\"> ";
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
        <tr bgcolor="#CCCCCC"> 
          <td colspan="3"> 
            <div align="center"><font size="2" face="Verdana, Arial, Helvetica, sans-serif"><b>LISTA 
              de GRUPOS</b></font></div>
          </td>
        </tr>
        <tr> 
          <td width="35%"> 
            <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Nombre</b></font></div>
          </td>
          <td width="34%"> 
            <div align="center"><font size="1"><font face="Verdana, Arial, Helvetica, sans-serif"><b>Funcion</b></font></font></div>
          </td>
          <td width="31%"> 
            <div align="center"><font size="1" face="Verdana, Arial, Helvetica, sans-serif"><b>Status</b></font></div>
          </td>
        </tr>

<?php
	// Fetch rows from AuthUser table and display ALL users
	$qQuery = "SELECT * FROM authteam ORDER BY id";
	
	// OLD CODE - DO NOT REMOVE
	// $result = mysql_db_query($dbname, $qQuery);
	
	// REVISED CODE
	$result = mysql_query($qQuery);
	
	$row = mysql_fetch_array($result);
	while ($row) {  		
		print "<tr>"; 
        print "  <td width=\"35%\">";
        print "    <div align=\"left\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">";
		print "		<a href=\"authgroup.php?act=Edit&teamname=".$row["teamname"]."&teamlead=".$row["teamlead"]."&status=".$row["status"]."\">";
		print 		$row["teamname"];
		print "		</a>";
		print "	   </font></div>";
        print "  </td>";
        print "  <td width=\"34%\">";
        print "    <div align=\"center\"><font face=\"Verdana, Arial, Helvetica, sans-serif\" size=\"1\">".$row["teamlead"]."</font></div>";
        print "  </td>";
        print "  <td width=\"31%\">";
        print "    <div align=\"right\"><font size=\"1\" face=\"Verdana, Arial, Helvetica, sans-serif\">".($row["status"])."</font></div>";
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
