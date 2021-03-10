<?php
  session_start();
  
  session_register("id","file","tda","usr","Venta");
  
  $_SESSION['usr']='Admin';

  $Orden=$_REQUEST[Orden];

  include("lib/kaplib.php");

  //$link=mysql_connect('localhost','Admin','facha');

  $link=conectarse();

  $op=$_REQUEST[op];

  if($op=="cnc"){

     $lUp=mysql_query("delete from ot where orden='$Orden' limit 1",$link);
     $lUp=mysql_query("delete from otd where orden='$Orden'",$link);
     $lUp=mysql_query("delete from result where orden='$Orden'",$link);

  }elseif($op=="en"){

      $lUp=mysql_query("update ot set institucion = '$_REQUEST[Institucion]' where orden='$Orden'",$link);

  }elseif($op=="sa"){

      $lUp=mysql_query("update ms set status = 'ABIERTA' where salida='$Salida' and tda='$_REQUEST[Tienda]'",$link);
  }

  $OtA=mysql_query("select * from ot where orden ='$Orden'",$link);

  $Ot=mysql_fetch_array($OtA);

  if($op=="rmd" or $op=="en"){	

	  $OtD=mysql_query("select nombrec from cli where $Ot[cliente] = cli.cliente",$link);

	  $Otc=mysql_fetch_array($OtD);
  }

  $lAg=$cKey=='NUEVO';

  $Fecha=date("Y-m-d");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title>Cancela</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
</head>

<body>
<p>
<div align="center"><font color="#0099FF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><strong>Cancelacion
  de Ordenes de trabajo</strong></font></div>
</p>
<form name="form1" method="post" action="xorden.php?op=rmd">
  <p><font color="#0099FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">No.Orden:
    &nbsp;
    <input name="Orden" type="text" id="Orden" size="6" maxlength="6" value ='<?php echo $Ot[orden]; ?>'>
    <input type="submit" name="Submit" value="Busca">
    </font></p>
</form>
<form name="form2" method="post" action="xorden.php?op=cnc">
  <div align="center">
    <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Orden
      No : <?php echo $Ot[orden];?> Nombre Paciente: <?php echo $Otc[nombrec];?></font></p>
    <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha:
      <input name="textfield2" type="text" size="10" value ='<?php echo $Ot[fecha]; ?>'>
      </font></p>
    <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Importe:
      <input name="textfield3"  type="number" size="8" value ="<?php echo number_format($Ot[importe],'2'); ?>">
      </font></p>
    <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Medico:
  <input name="textfield4" type="text" size="8" maxlength="10" value ='<?php echo $Ot[medico]; ?>'>
  </font></p>
    <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Recepcionista:
      <input name="textfield5" type="text" size="8" value ='<?php echo $Ot[recepcionista]; ?>'>
      </font></p>
    <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Status:<?php echo $Ot[status];?>
  	 &nbsp;&nbsp;&nbsp;
	</font></p>
    <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">Institucion:<?php echo $Ot[institucion];?>
       &nbsp;&nbsp;&nbsp;
    </font></p>
  <p><font color="#0033FF" size="2" face="Verdana, Arial, Helvetica, sans-serif">
  <input type="submit" name="Submit2" value="Elimina esta Orden">
  <input type="hidden" name="Orden" value=<?php echo $Orden; ?>>
  </font></p>
  </div>
</form>
<hr noshade style="color:3366FF;height:2px">
<form name="form3" method="post" action="xorden.php?op=en">
  <p><font color="#0099FF" size="2" face="Geneva, Arial, Helvetica, sans-serif">Cambia Institucion
    de la Orden :
    <input name="Orden" type="text" size="7">
    &nbsp;&nbsp;&nbsp;
    por la Institucion :
    <input name="Institucion" type="text" size="1">
    <input type="submit" name="Submit3" value="Cambiar">
    </font></p>
</form>
<hr noshade style="color:3366FF;height:2px">
</body>
</html>
