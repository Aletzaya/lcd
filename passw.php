<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Laboratorio Clinico Duran</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script language="JavaScript" type="text/JavaScript">
<!--
function Ventana(url){
   window.open(url,"venini","width=950,height=650,left=10,top=10,scrollbars=yes,location=no,dependent=yes,resizable=yes")
}
function cFocus(){
  document.form1.user.focus();
}
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>

<body onload="cFocus()">
    <tr>
      <td width="13%" height="387"> <div align="center">
      <p>&nbsp;</p>
      <p>&nbsp;</p>
      <p>&nbsp;</p>
    </div>
    <table width="100%" height="193" border="0" bordercolor="#333333">
      <form name="form1" method="post" action="menu.php">
        <tr>
          <td width="40%" bordercolor="#333333">&nbsp;</td>
          <td width="32%" bgcolor="#0099CC">
            <div align="center"><font color="#FFFFFF">Registro de usuarios</font></div></td>
          <td width="28%" height="23">
            <div align="center"></div></td>
        </tr>
        <tr> 
          <td bordercolor="#333333" bgcolor="#FFFFFF"></td>
          <td bordercolor="#333333" bgcolor="#FFFFFF"><p>&nbsp;</p>
            <p>&nbsp;&nbsp;&nbsp;&nbsp;<font color="#009966" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;&nbsp;&nbsp;<font color="#006699"> 
              </font><font color="#009966" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#006699"> 
              </font></font><font color="#006699">Clave &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input type="text" name="user" size="10" class="imputbox">
              </font></font></p>
            <p><font color="#006699" size="2" face="Verdana, Arial, Helvetica, sans-serif">&nbsp;Password 
              &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; 
              <input type="password" name="pass" size="10" class="imputbox">
              </font></p>
            <p>&nbsp;</p>
            <div align="center"> 
              <p><font color="#006699" size="2" face="Verdana, Arial, Helvetica, sans-serif"> 
                <input name=submit type=submit value="  Entrar  " class="botones" >
                </font></p>
              <p><font color="#009966" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#009966" size="2" face="Verdana, Arial, Helvetica, sans-serif"><font color="#006699">
                <?
               // Mostrar error de Autentificación.
               include ("aut_mensaje_error.inc.php");
               if (isset($error_login)){
                  echo "<font face='Verdana, Arial, Helvetica, sans-serif' size='1' color='#FF0000'>Error: $error_login_ms[$error_login]";
               }
            ?>
                </font></font></font></p>
            </div></td>
          <td height="162" bordercolor="#333333" bgcolor="#FFFFFF"> <p>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
            </td>
        </tr>
      </form>
    </table>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p>
        <p align="center">&nbsp;</p></td>
    </tr>
</body>
</html>