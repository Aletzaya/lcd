<?php
  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $link=conectarse();

  $Usr=$check['uname'];

  if(!isset($_REQUEST[$Vta])){$Vta=$_SESSION['Venta_ot'];}

  $Fecharec=date("Y-m-d");
  $cSqlA=mysql_query("select medico from otnvas where usr='$Usr' and venta='$Vta'",$link);
  $cSql=mysql_fetch_array($cSqlA);
  $Abono=$_REQUEST[Abono];
  $op=$_REQUEST[op];
  $Tpago=$_REQUEST[Tpago];

?>
<html>
<head>
<style type="text/css">
<!--
a.ord:link {
    color: #FFFFFF;
    text-decoration: none;
}
a.ord:visited {
    color: #FFFFFF;
    text-decoration: none;
}
a.ord:hover {
    color: #00CC33;
    text-decoration: underline;
}
-->
</style>
<title><?php echo $Titulo;?></title>
<script language="JavaScript1.2">
function cFocus(){
  document.form1.Abono.focus();
}

function Valido() {   
        if(document.form1.Abono.value > <?php echo $Abono; ?>){
			alert("Revise la Cantidad a Abonar")   
            return false 
		} else {
			    if(document.form1.Abono.value < 0){
					alert("Revise la Cantidad a Abonar")   
            		return false 
				} else {
	        		return true   
				}
		}
}


</script>
</head>
<body bgcolor="#FFFFFF" onload="cFocus()">
<table width="100%" border="0">
  <tr>
    <td width="14%" height="59"><div align="left"><img src="lib/logo2.jpg" width="100" height="80"></div></td>
    <td width="71%">
      <div align="center"><img src="lib/labclidur.jpg" width="350" height="50">
      </div></td>
    <td width="1%">&nbsp;</td>
    <td width="14%">&nbsp;</td>
  </tr>
</table>
<table width="100%" border="0">
  <tr bgcolor="#6633FF">
    <td width="82%" bgcolor="#6633ff"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="images/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
    </td>
    <td width="18%" height="23"><div align="right">
</div></td></tr>
</table>
<hr noshade style="color:00cc33;height:1px">
<br>
<div align='center'>
  <p><font color="#0000CC" size="4" face="Times New Roman, Times, serif"><strong>Datos Complementarios para generar la orden de estudio</strong></font></p>
  <table width="95%" height="180" border="0" >
    <tr>
      <td>
       <form name='form1' method='post' action='ordenesnvas.php?Vta=<?php echo $Vta;?>&op=Fn' onSubmit='return Valido();'>
          <p><div align="center"><font color='#0000CC' size='2'>No.receta :
          <input name='Receta' type='text' size='6'>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Fec.Receta :
          <input name='Fechar' type='text' size='10' value='<? echo $Fecharec;?>'>
          &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;IMPORTE : $<STRONG> &nbsp;&nbsp;<?php echo number_format($Abono,"2");?> </STRONG></div></p>
          <p align="center">Tipo de pago :
          <select name='Tpago'>
          <option value='Efectivo'>Efectivo</option>
          <option value='Tarjeta'>Tarjeta</option>
          <option value='Cheque'>Cheque</option>
          <option selected value ='Efectivo'>Efectivo</option>
          </select>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
          </p>
			 <p align='center'>
          Cant/Abonar $:
          <input name='Abono' type='text' size='6'>
          </p>
          <p align='center'>
          <?php
			 if($cSql[0]=='MD'){
			 	echo "Nombre del Medico:";
                echo "<input name='Medicon' type='text' size='30' value='A QUIEN CORRESPONDA'>";
			 }else{
  		        echo "<input type='hidden' name='Medicon' value='A QUIEN CORRESPONDA'>";
			 }
          ?>
          </p>          
          <p><div align="center"><font color="#0000CC" size="2">Diagnostico Medico</font></div></p>
          <div align="center"><TEXTAREA NAME="Diagmedico" cols="60" rows="3" ></TEXTAREA></div>
          <p>
          <div align="center"><font color="#0000CC" size="2">O b s e r v a c i o n e s </font></div>
          </p>
          <div align="center"><TEXTAREA NAME="Observaciones" cols="60" rows="3" ></TEXTAREA></div>
          <a href="ordenesnvas.php?Vta=<? echo $Vta;?>"><img src="images/SmallExit.bmp" border="0"></a>
          &nbsp;&nbsp;<input type="submit" name="Genera" value="Imprime">
       </form>
       </td>
    </tr>
  </table>
  <p>&nbsp; </p>
 </div>
  <tr>
  <td colspan='5'>&nbsp;</td>
</tr>
<td width="416" valign="top">
</td>
</body>
</html>