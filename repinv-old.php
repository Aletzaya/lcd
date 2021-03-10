<?php

  session_start();

  require("lib/lib.php");

  $link       = conectarse();
  $Fecha      = date('Y-m-d');

  $Titulo     = "Reporte de inventario al $Fecha";
  $busca      = $_REQUEST[busca];
  
  if($busca == 'ini'){

     $_SESSION["cVarVal"] = array('','','','');   //0.-Inventrio,1.-Depto,2.-Subdepto,3.-status
        
  }
  
  if(isset($_REQUEST[Existencia])){
     if($_REQUEST[Existencia] == 'Surtir'){    
         $_SESSION['cVarVal'][0] =  " existencia <= min ";                
     }elseif($_REQUEST[Existencia] == 'Excedido'){
         $_SESSION['cVarVal'][0] =  " existencia > max ";                         
     }else{
         $_SESSION['cVarVal'][0] =  "";                                  
     } 
  }
  
  if(isset($_REQUEST[Depto])){
     if($_REQUEST[Depto] == '*'){    
         $_SESSION['cVarVal'][1] =  "";                
     }else{
         $_SESSION['cVarVal'][1] =  " depto = '" .$_REQUEST[Depto] ."'";                                  
     } 
  }
  

  if(isset($_REQUEST[Subdepto])){
     if($_REQUEST[Subdepto] == '*'){    
         $_SESSION['cVarVal'][2] =  "";                
     }else{
         $_SESSION['cVarVal'][2] =  " subdepto = '" .$_REQUEST[Subdepto]."'";                                  
     } 
  }
  
  if(isset($_REQUEST[Status])){
     if($_REQUEST[Status] == '*'){    
         $_SESSION['cVarVal'][3] =  "";                
     }else{
         $_SESSION['cVarVal'][3] =  " status = '" .$_REQUEST[Status]."'";                                  
     } 
  }

  $cExistencia = $_SESSION[cVarVal][0];
  $cDepto      = $_SESSION[cVarVal][1];
  $cSubdepto   = $_SESSION[cVarVal][2];
  $cStatus     = $_SESSION[cVarVal][3];        
  
  $cWhere      = "";
  
  if($cExistencia <> ''){
    $cWhere  = $cExistencia;  
  }
  
  if($cDepto <> ''){
     if($cWhere == ''){ 
        $cWhere  = $cDepto;  
     }else{
        $cWhere  = $cWhere . " AND " . $cDepto;           
     }   
  }

  if($cSubdepto <> ''){
     if($cWhere == ''){ 
        $cWhere  = $cSubdepto;  
     }else{
        $cWhere  = $cWhere . " AND " . $cSubdepto;           
     }   
  }

  if($cStatus <> ''){
     if($cWhere == ''){ 
        $cWhere  = $cStatus;  
     }else{
        $cWhere  = $cWhere . " AND " . $cStatus;           
     }   
  }

if($cWhere <> ''){
  $cWhere = " where " . $cWhere;  
}  
 
//echo "Los valores son  exi:  $cExistencia, Depto: $cDepto  , Subd: $cSubdepto, status: $cStatus";        

  
$OtA = mysql_query("SELECT * FROM invl $cWhere ORDER BY invl.descripcion");
// echo "SELECT * FROM invl $cWhere ORDER BY invl.descripcion";

require ("config.php");

  //$Gfont2="<font size='1' face='Verdana, Arial, Helvetica, sans-serif' color='#000099'> &nbsp; ";
  //$Gfont22="<font size='2' face='Verdana, Arial, Helvetica, sans-serif' color='#000000'> &nbsp; ";

?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF">

<?php

headymenu($Titulo,0);

/*if($Recibio == '*'){
   	echo "$Gfont2 &nbsp; Todas las salidas ";
}else{
   	echo "$Gfont2 Salidas para $Recibio ";
}
*/
echo "<table width='98%' border='0' cellpadding='1' cellspacing='1' bordercolor=#CCCCCC><tr height='26' background='lib/bartit.gif'>";
echo "<td>$Gfont2 <b> Producto</b></td><td>$Gfont2 <b>Descripcion</b></td><td>$Gfont2 <b>Marca</b></td><td>$Gfont2 <b>Depto</b></td>
<td>$Gfont2 <b>Subdepto</b></td><td>$Gfont2 <b>Min.</b></td><td>$Gfont2 <b>Max</b></td><td>$Gfont2 <b>Gral.</b></td><td>$Gfont2 <b>Matriz</b></td>
<td>$Gfont2 <b>Tpex</b></td><td>$Gfont2 <b>HF</b></td><td>$Gfont2 <b>Reyes</b></td><td>$Gfont2 <b>Exist</b></td>
<td>$Gfont2 <b>Presentacion</b></td><td>$Gfont2 <b>Costo</b></td><td>$Gfont2 <b>Importe</b></td><td>$Gfont2 <b>Status</b></td></tr>";

while($reg=mysql_fetch_array($OtA)){

      if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

      echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		echo "<td>$Gfont2 $reg[clave]</td>";
		echo "<td>$Gfont2 $reg[descripcion]</td>";
		echo "<td>$Gfont2 $reg[marca]</td>";
		echo "<td>$Gfont2 $reg[depto]</td>";
		echo "<td>$Gfont2 $reg[subdepto]</td>";
		echo "<td>$Gfont2 $reg[min]</td>";
		echo "<td>$Gfont2 $reg[max]</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[invgral],"0")."</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[invmatriz],"0")."</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[invtepex],"0")."</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[invhf],"0")."</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[invreyes],"0")."</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[existencia],"0")."</td>";
		echo "<td>$Gfont2 $reg[presentacion]</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[costo],"2")."</td>";
		echo "<td align='right'>$Gfont2 ".number_format($reg[existencia]*$reg[costo],"2")."</td>";
		echo "<td>$Gfont2 $reg[status]</td>";
		echo "</tr>";
		$Cnt += $reg[existencia];
		$Imp += $reg[existencia]*$reg[costo];

      $nRng++;
		
}	

echo "<tr>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td align='right'>$Gfont2 <b> Totales ----></b></td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td>$Gfont2 </td>";
echo "<td align='right' colspan='2'>$Gfont2 <b>".number_format($Cnt,"2")."</b></td>";
echo "<td align='right' colspan='2'>$Gfont2 <b>".number_format($Imp,"2")."</b></td>";
echo "</tr></table>";

echo "<input type='submit' name='Imprimir' value='Imprimir'  onCLick='print()'> &nbsp; &nbsp; &nbsp; ";
echo "<a href='repinv.php?busca=ini'>Limpia pantalla</a>";

echo "<table width='98%' class='textosItalicos' border='1' cellpadding='1' cellspacing='1' bordercolor=#CCCCCC>";
echo "<tr>";
echo "<td>";

echo "<form name='form1' method='get' action='repinv.php'>";

    echo "Existencias: &nbsp;";
    echo "<select name='Existencia' onchange=this.form.submit()>";
    echo "<option value='Todos'>Todos</option>";
    echo "<option value='Surtir'>Surtir</option>";
    echo "<option value='Excedido'>Excedido</option>";
    if($cExistencia=='*' OR $cExistencia == ''){
	echo "<option selected value='Todos'>Todos</option>";
    }elseif($cExistencia == ' existencia <= min '){
	echo "<option selected value='Surtir'>Surtir</option>";
    }else{
	echo "<option selected value='Excedido'>Excedido</option>";        
    }
    echo "</select>";
echo "</form>";

echo "</td><td>";

echo "<form name='form2' method='get' action='repinv.php'>";

echo "Departamento: &nbsp;";
$cSub=mysql_query("SELECT dep.departamento,dep.nombre FROM dep ");
echo "<SELECT name='Depto' class='textosItalicos' onchange=this.form.submit()>";
while ($dep=mysql_fetch_array($cSub)){
     echo "<option value='$dep[0]'>$dep[0]: ".ucwords(strtolower($dep[1]))."</option>";
     if($dep[0] == $_REQUEST[Depto]){
        $cDsp1 = ucwords(strtolower($dep[1]));  
     }    
}
echo "<option value='*'>* Todos</option>";
if($_REQUEST[Depto]==''){
    echo "<option selected value=''></option>";
}else{
    echo "<option selected value='$_REQUEST[Depto]'>$cDsp1</option>";    
}    
echo "</SELECT>";

echo "</form>";

echo "</td><td>";

echo "<form name='form3' method='post' action='repinv.php'>";

echo "Subdepto: &nbsp;";
$cSubd=mysql_query("SELECT depd.departamento,depd.subdepto FROM depd where depd.departamento=$_REQUEST[Depto]");
echo "<SELECT name='Subdepto' onchange=this.form.submit()>";
while ($subdep=mysql_fetch_array($cSubd)){
	 echo "<option value='$subdep[1]'>$subdep[1]</option>";
}
echo "<option value='*'>* Todos</option>";
echo "<option selected value='$_REQUEST[Subdepto]'>$_REQUEST[Subdepto] $Todos</option>";
echo "</SELECT>";

echo "</form>";

echo "</td><td>";

echo "<form name='form4' method='post' action='repinv.php'>";
echo "Status: &nbsp;";
echo "<select name='Status' onchange=this.form.submit()>";
echo "<option value='Activo'>Activo</option>";
echo "<option value='Inactivo'>Inactivo</option>";
echo "<option value='*'>Todos</option>";
if($_REQUEST[Status]=='*'){
	$Todos='Todos';
}
if($cSt==' '){
	echo "<option selected value='*'>Todos</option>";
}else{
	echo "<option selected value='$cSt'>$cSt</option>";
}
echo "</select>";

echo "</form>";

echo "</td></tr></table>";

//echo "<input type='submit' name='Boton' value='Enviar'>";


CierraWin();

mysql_close();

echo "</body>";

echo "</html>";

?>
