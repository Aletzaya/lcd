<?php

session_start();

require("lib/lib.php");

$link=conectarse();
/*
 if (!isset($_REQUEST[filtro])){
      $filtro = '*';
  }else{
      $filtro    = $_REQUEST[filtro];       
  }

  if (!isset($_REQUEST[filtro3])){
      $filtro3 = '*';
  }else{
      $filtro3    = $_REQUEST[filtro3];       
  }

  if (!isset($_REQUEST[filtro5])){
      $filtro5 = '*';
  }else{
      $filtro5    = $_REQUEST[filtro5];       
  }

  if (!isset($_REQUEST[filtro7])){
      $filtro7 = '*';
  }else{
      $filtro7    = $_REQUEST[filtro7];       
  }

  if (!isset($_REQUEST[filtro9])){
      $filtro9 = '*';
  }else{
      $filtro9    = $_REQUEST[filtro9];       
  }

  if (!isset($_REQUEST[filtro11])){
      $filtro11 = '*';
  }else{
      $filtro11    = $_REQUEST[filtro11];       
  }

  if (!isset($_REQUEST[filtro13])){
      $filtro13 = '*';
  }else{
      $filtro13    = $_REQUEST[filtro13];       
  }

  if (!isset($_REQUEST[filtro15])){
      $filtro15 = '*';
  }else{
      $filtro15    = $_REQUEST[filtro15];       
  }

  if (!isset($_REQUEST[filtro17])){
      $filtro17 = '*';
  }else{
      $filtro17    = $_REQUEST[filtro17];       
  }

  if (!isset($_REQUEST[filtro19])){
      $filtro19 = '*';
  }else{
      $filtro19    = $_REQUEST[filtro19];       
  }

  if (!isset($_REQUEST[filtro21])){
      $filtro21 = '*';
  }else{
      $filtro21    = $_REQUEST[filtro21];       
  }

  if (!isset($_REQUEST[filtro23])){
      $filtro23 = '*';
  }else{
      $filtro23    = $_REQUEST[filtro23];       
  }

 if($filtro<>'*'){
    $filtro2="and est.depto='$filtro'";
 }else{
    $filtro2=" ";
 }

 if($filtro3<>'*'){
    $filtro4="and est.subdepto='$filtro3'";
 }else{
    $filtro4=" ";
 }

 if($filtro5<>'*'){
    $filtro6="and est.proceso='$filtro5'";
 }else{
    $filtro6=" ";
 }
 
 if($filtro7<>'*'){
    $filtro8="and est.bloqbas='$filtro7'";
 }else{
    $filtro8=" ";
 }
 
 if($filtro9<>'*'){
    $filtro10="and est.activo='$filtro9'";
 }else{
    $filtro10=" ";
 }

 if($filtro11<>'*'){
    $filtro12="and est.bloqeqp='$filtro11'";
 }else{
    $filtro12=" ";
 }

 if($filtro13<>'*'){
    $filtro14="and est.bloqmue='$filtro13'";
 }else{
    $filtro14=" ";
 }

 if($filtro15<>'*'){
    $filtro16="and est.bloqcon='$filtro15'";
 }else{
    $filtro16=" ";
 }

 if($filtro17<>'*'){
    $filtro18="and est.bloqdes='$filtro17'";
 }else{
    $filtro18=" ";
 }

 if($filtro19<>'*'){
    $filtro20="and est.bloqadm='$filtro19'";
 }else{
    $filtro20=" ";
 }

 if($filtro21<>'*'){
    $filtro22="and est.bloqatn='$filtro21'";
 }else{
    $filtro22=" ";
 }

 if($filtro23<>'*'){
    $filtro24="and est.base='$filtro23'";
 }else{
    $filtro24=" ";
 }
*/
$Titulo = "Reporte de catalogo de Proveedores";

//$cOtA="select * from est where est.estudio<>'' $filtro2 $filtro4 $filtro6 $filtro8 $filtro10 $filtro12 $filtro14 $filtro16 $filtro18 $filtro20 $filtro22 $filtro24 order by est.descripcion";

$cOtA="select * from prv order by prv.id";

$OtA  = mysql_query($cOtA,$link);

require ("config.php");

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php
headymenu($Titulo, 0);

echo "<table align='center' width='95%' border='0'>";
/*
echo "<td align='left'>$Gfont<b><font size='1'>Departamento</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
$depA=mysql_query("SELECT departamento,nombre FROM dep order by departamento");
while($dep=mysql_fetch_array($depA)){
    echo "<option value=$dep[departamento]> $dep[departamento]&nbsp;$dep[nombre]</option>";
    if($dep[departamento]==$filtro){$DesSuc=$dep[nombre];}
}
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro $DesSuc</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";

echo "<td align='left'>$Gfont<b><font size='1'>Subdepartamento</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro3' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
$subdepA=mysql_query("SELECT id,subdepto,nombre FROM depd where depd.departamento=$filtro order by id");
while($subdep=mysql_fetch_array($subdepA)){
    echo "<option value=$subdep[subdepto]> $subdep[id]&nbsp;$subdep[subdepto]</option>";
    if($subdep[subdepto]==$filtro3){$Desdep=$subdep[subdepto];}
}
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro3</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";

echo "<td align='left'>$Gfont<b><font size='1'>Proceso</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro5' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='TOMA SANGUINEA'>TOMA SANGUINEA</option>";
echo "<option value='RECOLECCION DE MUESTRA'>RECOLECCION DE MUESTRA</option>";
echo "<option value='REALIZACION DE ESTUDIOS'>REALIZACION DE ESTUDIOS</option>";
echo "<option value='TOMA DE MUESTRA CORPORAL'>TOMA DE MUESTRA CORPORAL</option>";
echo "<option value='SERVICIO'>'SERVICIO'</option>";
echo "<option value='MIXTO'>MIXTO</option>";
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro5</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";

echo "<td align='left'>$Gfont<b><font size='1'>Status</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro9' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Si'>Activo</option>";
echo "<option value='No'>Inactivo</option>";
    if($filtro9=='Si'){$Desta='Activo';}elseif($filtro9=='No'){$Desta='Inactivo';}else{$Desta='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desta</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Basicos</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro7' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro7=='Si'){$Desbas='Cerrado';}elseif($filtro7=='No'){$Desbas='Abierto';}else{$Desbas='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desbas</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Equipos</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro11' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro11=='Si'){$Deseqp='Cerrado';}elseif($filtro11=='No'){$Deseqp='Abierto';}else{$Deseqp='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Deseqp</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Muestras</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro13' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro13=='Si'){$Desmue='Cerrado';}elseif($filtro13=='No'){$Desmue='Abierto';}else{$Desmue='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desmue</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Cont</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro15' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro15=='Si'){$Descon='Cerrado';}elseif($filtro15=='No'){$Descon='Abierto';}else{$Descon='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Descon</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Descrip</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro17' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro17=='Si'){$Desdes='Cerrado';}elseif($filtro17=='No'){$Desdes='Abierto';}else{$Desdes='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desdes</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Admin</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro19' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro19=='Si'){$Desamd='Cerrado';}elseif($filtro19=='No'){$Desamd='Abierto';}else{$Desamd='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desamd</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Atn</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro21' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='No'>Abierto</option>";
echo "<option value='Si'>Cerrado</option>";
    if($filtro21=='Si'){$Desatn='Cerrado';}elseif($filtro21=='No'){$Desatn='Abierto';}else{$Desatn='*';}
echo "<option selected value='*'>$Gfont <font size='-1'>$Desatn</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<td align='left'>$Gfont<b><font size='1'>Tipo Est</b></font>";
echo "<form name='form' method='post' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<select size='1' name='filtro23' class='Estilo9' onchange=this.form.submit()>";
echo "<option value='*'>Todos*</option>";
echo "<option value='Individual'>Individual</option>";
echo "<option value='Asociado'>Asociado</option>";
echo "<option value='Agrupado'>Agrupado</option>";
echo "<option value='Mixto'>Mixto</option>";
echo "<option value='Asociado'>Combinado</option>";
echo "<option selected value='*'>$Gfont <font size='-1'>$filtro23</option></p>";       
echo "</select>";
echo"</b></td>";
echo "</form>";
echo "</font></td><td>";

echo "<form name='form2' method='get' action='repcatalogoest.php?Sort=Asc&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&filtro11=$filtro11&filtro13=$filtro13&filtro15=$filtro15&filtro17=$filtro17&filtro19=$filtro19&filtro21=$filtro21&filtro23=$filtro23'>";
echo "<input type='submit' name='Imprimir' value='Imprimir' onCLick='print()'>";
echo "</form>";
echo "</td><td aling='center'><a href=javascript:winuni('repcatalogoestpdf.php?pagina=$pagina&Sort=Asc&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9')><img src='pdfenv.png' alt='pdf' border='0'></a></td></tr></table>";

$Gfon = "<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'><b>";
echo "<table width='97%' align='center' border='0'>";
$Gfont = "<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#444444'>";
*/

$Tit = "<br><tr bgcolor='#a2b2de'><td align='center'>$Gfont #</td><td align='center'> </td><td align='center' width='30%'>$Gfont Nombre</td><td align='center'>$Gfont  Alias</td><td align='center'>$Gfont RFC</td><td align='center'>$Gfont Telefono</td><td align='center'>$Gfont Nota</td><td align='center'>$Gfont Resp.Prv</td><td align='center'>$Gfont Usr Mod.</td><td align='center'>$Gfont Fecha Mod.</td></tr>";

echo $Tit;

while ($reg = mysql_fetch_array($OtA)) {

    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;
    
    $nRng++;

   //            echo "<td align='center'><a href='$cLink?busca=$registro[id]'><img src='lib/edit.png' alt='Modifica Registro' border='0'></td>";

    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';><td align='center'>$Gfont $reg[id]</td><td align='center'><a href=javascript:wingral('proveedorespdf.php?busca=$reg[id]')><img src='images/printdigital.png' alt='Impresion de registro' border='0' width='22'></td><td>$Gfont <a href=javascript:wingral('proveedoresedet.php?busca=$reg[id]')> $reg[nombre]</a></font></td><td>$Gfont $reg[alias]</font></td><td>$Gfont $reg[rfc]</font></td><td>$Gfont $reg[telefono]</font></td><td align='center'>$Gfont $reg[nota]</font></td><td align='center'>$Gfont $reg[respprv]</font></td><td align='left'>$Gfont $reg[usrmod]</font></td><td align='center'>$Gfont $reg[fechamod]</font></td></tr>";

}

echo "<tr bgcolor='#a2b2de'><td>&nbsp;</td><td>&nbsp;</td><td align='center'colspan='2'>$Gfont <b>Total de Proveedores:  $nRng </b></font></td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";

echo "</table>";


mysql_close();
?>

</body>

</html>