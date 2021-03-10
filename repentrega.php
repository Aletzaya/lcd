<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/lib.php");
  
    date_default_timezone_set("America/Mexico_City");

  $link=conectarse();

  $Usr=$check['uname'];

  $FecI=$_REQUEST[FecI];
  $FecF=$_REQUEST[FecF];

  $Fechai=$FecI;

  $Fechaf=$FecF;

  $Titulo=$_REQUEST[Titulo];
	
  $Fecha=date("Y-m-d");

  $Hora=date("H:i");
  
	$Sucursal     =   $_REQUEST[Sucursal];
	//$Sucursal     =   $Sucursal[0];
	$sucursalt = $_REQUEST[sucursalt];
	$sucursal0 = $_REQUEST[sucursal0];
	$sucursal1 = $_REQUEST[sucursal1];
	$sucursal2 = $_REQUEST[sucursal2];
	$sucursal3 = $_REQUEST[sucursal3];
	$sucursal4 = $_REQUEST[sucursal4];

	$Institucion=$_REQUEST[Institucion];
	$Mostrador=$_REQUEST[Mostrador];
	$Domicilio=$_REQUEST[Domicilio];
	$Digital=$_REQUEST[Digital];

?>
<html>
<head>
<title>Sistema de Laboratorio clinico</title>
</head>
<body>
<?php

  $InstA   = mysql_query("SELECT nombre FROM inst WHERE institucion='$Institucion'");
  $NomI    = mysql_fetch_array($InstA);
  
	$Sucursal= "";
	
	if($sucursalt=="1"){  
	
		$Sucursal="*";
		$Sucursal2= " * - Todas ";
	}else{
	
		if($sucursal0=="1"){  
			$Sucursal= " ot.suc=0";
			$Sucursal2= "Administracion - ";
		}
		
		if($sucursal1=="1"){ 
			$Sucursal2= $Sucursal2 . "Laboratorio - "; 
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=1";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=1";
			}
		}
		
		if($sucursal2=="1"){
			$Sucursal2= $Sucursal2 . "Hospital Futura - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=2";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=2";
			}
		}
		
		if($sucursal3=="1"){
			$Sucursal2= $Sucursal2 . "Tepexpan - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=3";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=3";
			}
		}
		
		if($sucursal4=="1"){
			$Sucursal2= $Sucursal2 . "Los Reyes - ";
			if($Sucursal==""){
				$Sucursal= $Sucursal . " ot.suc=4";
			}else{
				$Sucursal= $Sucursal . " OR ot.suc=4";
			}
		}
	}

	if($Institucion==''){
		$insttu="";
	}else{
		$insttu=" and ot.institucion=$Institucion";
	}

	if($Mostrador==''){
		$mostrad="";
	}else{
		$mostrad=" and ot.tentregamost=$Mostrador";
	}

	if($Domicilio==''){
		$domicil="";
	}else{
		$domicil=" and ot.tentregamed=$Domicilio";
	}

	if($Digital==''){
		$digit="";
	}elseif($Digital=="1"){
		$digit=" and ot.entemailpac=1";
	}elseif($Digital=="2"){
		$digit=" and ot.entwhatpac=1";
	}elseif($Digital=="3"){
		$digit=" and ot.entemailmed=1";
	}elseif($Digital=="4"){
		$digit=" and ot.entwhatmed=1";
	}elseif($Digital=="5"){
		$digit=" and ot.entemailinst=1";
	}

if($Sucursal=="*"){

	$cSql="SELECT ot.suc, ot.orden, ot.fecha, cli.nombrec, ot.institucion, ot.recepcionista, ot.tentregamost, ot.tentregamed, ot.tentregainst, ot.entemailpac, ot.entemailmed, ot.entemailinst, ot.entwhatpac, ot.entwhatmed, ot.entwhatinst
	FROM ot, cli
	WHERE ot.cliente = cli.cliente and ot.fecha>='$Fechai' and ot.fecha <='$Fechaf' $insttu $mostrad $domicil $digit order by ot.orden";

}else{

	$cSql="SELECT ot.suc, ot.orden, ot.fecha, cli.nombrec, ot.institucion, ot.recepcionista, ot.tentregamost, ot.tentregamed, ot.tentregainst, ot.entemailpac, ot.entemailmed, ot.entemailinst, ot.entwhatpac, ot.entwhatmed, ot.entwhatinst
	FROM ot, cli
	WHERE ot.cliente = cli.cliente and ot.fecha>='$Fechai' and ot.fecha <='$Fechaf' $insttu $mostrad $domicil $digit AND ($Sucursal) order by ot.orden";
}


$UpA=mysql_query($cSql,$link);

?>
<table width="100%" border="0">
  <tr> 
    <td width="27%"><div align="left"><img src="images/Logotipo%20Duran4.jpg" alt="" width="187" height="61"> 
      </div></td>
    <td width="73%"> <font size="4" face="Arial, Helvetica, sans-serif"> <div align="left"><strong>Laboratorio 
        Clinico Duran</strong><br><font size="1">
        <?php echo "$Fecha - $Hora"; ?><br>
        <font size="1"><?php echo "Relacion de entrega de Ordenes de trabajo del $Fechai al $Fechaf Sucursal: $Sucursal2 Institucion: $Institucion - $NomI[nombre]"; ?></font>&nbsp;</div></td>
  </tr>
</table>
<font face="Arial, Helvetica, sans-serif"> <font size="1">
<?php
    echo "<table align='center' width='98%' border='0' cellspacing='0' cellpadding='0'>";
    echo "<tr><td colspan='12'><hr noshade></td></tr>";
    echo "<th width='3%' align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Suc</font></th>";
    echo "<th width='6%' align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Inst-Orden</font></th>";
    echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'>Fecha</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Recep</font></th>";
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Mostrador</font></th>";		
    echo "<th align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Domicilio</font></th>";		
    echo "<th align='CENTER' colspan='4'><font size='1' face='Arial, Helvetica, sans-serif'>Digital</font></th>";		
    echo "<tr><td colspan='12'><hr noshade></td></tr>";
    $Ordenes=0;
/*	$contamostm=0;
	$contamostf=0;
	$contamostt=0;
	$contamostr=0;
    $contamostsr=0;   		*/
    while($rg=mysql_fetch_array($UpA)) {

    	if( ($nRng % 2) > 0 ){$Fdo='ffffff';}else{$Fdo='acdcc1';}    //El resto de la division;

        echo "<tr height='20' bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
    	echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[suc]</font></th>";
    	echo "<th align='right'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[institucion] -  $rg[orden]</font></th>";
    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[fecha]</font></th>";
    	echo "<th align='left'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[nombrec]</font></th>";
    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $rg[recepcionista]</font></th>";

    	if($rg[tentregamost]==1){
    		$mostrador='Matriz';
    		$contamostm++;
    	}elseif($rg[tentregamost]==2){
    		$mostrador='OHF';
    		$contamostf++;
    	}elseif($rg[tentregamost]==3){
    		$mostrador='Tepexpan';
    		$contamostt++;
    	}elseif($rg[tentregamost]==4){
    		$mostrador='Reyes';
    		$contamostr++;
    	}else{
    		$mostrador=' '; 
    		$contamostsr++;   		
    	}

    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $mostrador</font></th>";

    	if($rg[tentregamed]==1){
    		$domicilio='Paciente';
    		$contadomp++;
    	}elseif($rg[tentregamed]==2){
    		$domicilio='Medico';
    		$contadomm++;
    	}elseif($rg[tentregamed]==3){
    		$domicilio='Institucion';
    		$contadomi++;
    	}else{
    		$domicilio=' '; 
    		$contadomsr++;   		
    	}
    	
    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $domicilio</font></th>";

    	if($rg[entemailpac]==1){
    		$mailpac='@-Pac';
    		$contamailp++;
    		$contamailp2=1;
    	}else{
    		$mailpac=' ';    		
    	}

    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $mailpac</font></th>";

    	if($rg[entemailmed]==1){
    		$mailmed='@-Med';
    		$contamailm++;
    		$contamailm2=1;
    	}else{
    		$mailmed=' ';    		
    	}

    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $mailmed</font></th>";

    	if($rg[entwhatpac]==1){
    		$whatpac='W-Pac';
    		$contawhatp++;
    		$contawhatp2=1;
    	}else{
    		$whatpac=' ';    		
    	}
    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $whatpac</font></th>";
    	
    	if($rg[entwhatmed]==1){
    		$whatmed='W-Med';
    		$contawhatm++;
    		$contawhatm2=1;
    	}else{
    		$whatmed=' ';    		
    	}

    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $whatmed</font></th>";

    	if($rg[entemailinst]==1){
    		$whatinst='@-Inst';
    		$contamaili++;
    		$contamaili2=1;
    	}else{
    		$whatinst=' ';    		
    	}
    	
    	echo "<th align='center'><font size='1' face='Arial, Helvetica, sans-serif'> &nbsp; $whatinst</font></th>";

    	$conta=$contamailp2+$contamailm2+$contamaili2+$contawhatm2+$contawhatp2;

    	if($conta==0){
    		$conta2++;
    	}
 		$conta=$contamailp2=$contamailm2=$contamaili2=$contawhatm2=$contawhatp2=0;
        $Ordenes++;
        $nRng++;

     }
     echo "<th colspan='5' align='center'><font color='#FF0000' size='1' face='Arial, Helvetica, sans-serif'>$Obs</font></th>";
     echo "<tr><td colspan='12'><hr noshade></td></tr>";  

     echo "<br>";

	 $FecI=$_REQUEST[FecI];
  	 $FecF=$_REQUEST[FecF];

              
     echo "</table>";

    echo "<table align='center' width='70%' border='1' cellspacing='0' cellpadding='0'>";
    echo "<tr bgcolor='#59a77a'><td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'><b>A MOSTRADOR</b></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'><b>CNT</b></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'><b>A DOMICILIO</b></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'><b>CNT</b></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'><b>DIGITAL</b></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'><b>CNT</b></font></td></tr>";

 	echo "<tr><td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Matriz</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamostm</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Paciente</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contadomp</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>@-Pac</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamailp</font></td></tr>";

    echo "<tr><td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>O. H. Futura</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamostf</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Medico</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contadomm</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>W-Pac</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contawhatp</font></td></tr>";
	
	echo "<tr><td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Tepexpan</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamostt</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Institucion</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contadomi</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>@-Med</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamailm</font></td></tr>";

    echo "<tr><td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>Los Reyes</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamostr</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>S/R</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contadomsr</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>W-Med</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contawhatm</font></td></tr>";

    echo "<tr><td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>S/R</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamostsr</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>@-Inst</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$contamaili</font></td></tr>";

    $sr=$Ordenes-($contamailp+$contamailm+$contamaili+$contawhatm+$contawhatp);

    echo "<tr><td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'></td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>S/R</td>
    <td align='CENTER'><font size='1' face='Arial, Helvetica, sans-serif'>$conta2</font></td></tr>";

    echo "<tr bgcolor='#59a77a'><td align='center' colspan='6'><font size='1' face='Arial, Helvetica, sans-serif'><b>NUMERO TOTAL DE ORDENES: $Ordenes</b></td></tr>";

    echo "</table>";

	 echo "<div align='center'>";
	 echo "<p align='center'><font face='verdana' size='-2'><a href='pidedatos.php?cRep=29&fechas=1&FecI=$FecI&FecF=$FecF'>";
	 echo "Regresar</a></font>";
	 echo "</div>";
	
?>
</font></font> 
<?php
	echo "<div align='left'>";
	$FecI=$_REQUEST[FecI];
	$FecF=$_REQUEST[FecF];

	echo "<form name='form1' method='post' action='pidedatos.php?cRep=29&fechas=1&FecI=$FecI&FecF=$FecF'>";
	echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
	echo "</form>";
	echo "</div>";
?>
</body>
</html>