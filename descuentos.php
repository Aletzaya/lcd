<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");

  $op=$_REQUEST[op];

  $Vta=$_REQUEST[Vta];

  $Estudio=$_REQUEST[Estudio];

  $Usr=$check['uname'];

  $link=conectarse();?>

<html>
<head>
<title><?php echo $Titulo;?></title>
</head>

<?php

 echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

 headymenu($Titulo,0);

?>

<table width="87%" height="149" border="0">
  <tr>
    <td height="145"> 
      <div align="center">
        <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>
		  <?php 
		  if($op=="dtg"){
		     echo "Descuento para toda la orden de trabajo";
		  }else{
		     echo "Descuento por estudio";
		  }
		  ?>
		 </strong></font></p>
        <form name="form1" method="post" action="ordenesnvas.php?Vta=<?php echo $Vta;?>&op=<?php echo $op;?>">
          <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Razon :
          <input name="Razon" type="text" size="30"></font>
          </p>
          <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">% de Descuento :
          <input name="Descuento" type="text" size="5"></font>
          </p>
          <p>
          <input type='hidden' name='Estudio' value='<?php echo $Estudio; ?>' >
          <input type="submit" name="Submit" value="Enter">
          </p>
       </form>
      </div></td>
  </tr>
</table>
<p>&nbsp;</p>
<hr noshade style="color:66CC66;height:3px">
<td width="416" valign="top">
</td>
</body>
</html>
