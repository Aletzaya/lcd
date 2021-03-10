<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/libN.php");

  $link   = conectarse();

  $tamPag = 14;

  $pagina = $_REQUEST[pagina];
  
  $op      = $_REQUEST[op];

  $busca   = $_REQUEST[busca];

  $OrdenDef= "estudio";      //Orden de la tabla por default

  $Estudio = strtoupper($_REQUEST[Estudio]);

  $filtro    = $_REQUEST[filtro];       
  $filtro3    = $_REQUEST[filtro3];       
  $filtro5    = $_REQUEST[filtro5];       
  $filtro7    = $_REQUEST[filtro7];       
  $filtro9    = $_REQUEST[filtro9];  

$cSql   = "SELECT estudio,precio,descuento
    FROM otd
    WHERE orden='$busca'";

$Edit   = array("Est","Descripcion","Precio","<a class='vt' href='descuentosN.php?Vta=$Vta&op=dtg'>%Dto</a>","Importe","Elim","T/Entreg","Status","Etq","Obs","-","-","-","-","-","-","-","-","-","-");

$aPrg = array('Ninguno','Cliente frecuente','Apoyo a la salud','Chequeo medico','Empleado','Familiar','Medico','Especializado');

  if($op=="digi"){
    $lUp=mysql_query("UPDATE ot set tentregadig ='$_REQUEST[tentregadig]', entemailpac='$_REQUEST[entemailpac]',entemailmed='$_REQUEST[entemailmed]',entemailinst='$_REQUEST[entemailinst]',entwhatpac='$_REQUEST[entwhatpac]',entwhatmed='$_REQUEST[entwhatmed]' WHERE orden='$busca'",$link);
  }elseif($op=="most"){
    $lUp=mysql_query("UPDATE ot  set tentregamost ='$_REQUEST[tentregamost]' WHERE orden='$busca'",$link);
  }elseif($op=="medi"){
    $lUp=mysql_query("UPDATE ot set tentregamed ='$_REQUEST[tentregamed]' WHERE orden='$busca'",$link);
  }elseif($op=="Sv"){
    $lUp=mysql_query("UPDATE ot  set servicio='$_REQUEST[Servicio]' WHERE orden='$busca'",$link);
  }elseif($op=="dato"){
    $lUp=mysql_query("UPDATE ot set datoc = '$_REQUEST[Datoc]' WHERE orden='$busca'",$link);
  }elseif($op=="do"){
    $lUp=mysql_query("UPDATE ot set diagmedico='$_REQUEST[Diagmedico]' WHERE orden='$busca'",$link);
    $lUp=mysql_query("UPDATE ot set observaciones='$_REQUEST[Observaciones]' WHERE orden='$busca'",$link);
  }elseif($op=="rec"){
    $lUp=mysql_query("UPDATE ot set receta = '$_REQUEST[Receta]' WHERE orden='$busca'",$link);
  }elseif($op=="Fc"){   // Cambai fecha de etrega
    $lUp=mysql_query("UPDATE ot set fechae = '$_REQUEST[Fechae]' WHERE orden='$busca'",$link);
  }elseif($op=="Fc1"){   // Cambai hora de etrega
    $lUp=mysql_query("UPDATE ot set horae = '$_REQUEST[Horae]' WHERE orden='$busca'",$link);
  }elseif($op=="frec"){
    $lUp=mysql_query("UPDATE ot set fecharec='$_REQUEST[Fechar]' WHERE orden='$busca'",$link);
  }

require ("configN.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />

<title><?php echo $Titulo;?></title>

</head>


<?php

	echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

   headymenu2($Titulo,1);


 ?>

<script language="JavaScript1.2">
function ValSuma(){
var lRt;
lRt=true;
if(document.form3.SumaCampo.value=="CAMPOS"){lRt=false;}
if(!lRt){
	alert("Aun no as elegigo el campo a sumar, Presiona la flecha hacia abajo y elige un campo");
    return false;
}
	return true;
}

function cFocus(){
  document.form2.Estudio.focus();
}

function vacio(q) {
        for ( i = 0; i < q.length; i++ ) {
                if ( q.charAt(i) != " " ) {
                        return true
                }
        }
        return false
}

function Completo() {

        if( vacio(document.form4.Medico.value)  == false ) {
                alert("Son necesarios los datos de cliente y/o medico, para generar la orden de estudio")
                return false
        } else {
				if( vacio(document.form3.Cliente.value)  == false ) {
            	    alert("Son necesarios los datos de cliente y/o medico, para generar la orden de estudio")
                	return false
		        } else {
 					if(document.form3.Cliente.value=="0"){
							alert("Son necesarios los datos de cliente y/o medico, para generar la orden de estudio")
                			return false
					} else {
	        	        return true
					}
		        }
        }

}

function Msjadmvo($mens){
	if( $mens == ' ' ) {
		return true
	}else{
		alert($mens)
		return true
	}
}

function Valido() {   
//    if(document.form4.Abono.value > <?php echo $He[0]; ?>){
    if(document.form4.Abono.value > <?php echo $He[0]; ?>){
        alert("Revise la Cantidad a Abonar")   
        return false 
    } else {
        if(document.form4.Abono.value < 0){
            alert("Revise la Cantidad a Abonar")   
            return false 
        } else {
            return true   
        }
    }
}


function Ventana(url){
   window.open(url,"venord","width=750,height=500,left=40,top=50,scrollbars=yes,location=no,dependent=yes,resizable=yes")
}

</script>

<?php

$OtA  = mysql_query("SELECT * FROM ot WHERE orden='$busca'");
$Ot   = mysql_fetch_array($OtA);

$InsA = mysql_query("SELECT institucion,nombre,alias,descuento,condiciones FROM inst where status='ACTIVO' and institucion='$Ot[institucion]' order by institucion");
$Ins  = mysql_fetch_array($InsA);


$MedA = mysql_query("SELECT medico,nombrec,clasificacion,mail FROM med WHERE medico='$Ot[medico]'");
$Med  = mysql_fetch_array($MedA);

$CliA = mysql_query("SELECT nombrec,programa,observaciones,colonia,municipio,numveces,mail FROM cli WHERE cliente='$Ot[cliente]'");
$Cli  = mysql_fetch_array($CliA);

$HeA    = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
$He     = mysql_fetch_array($HeA);

$OtB = mysql_query("SELECT * FROM otd WHERE orden='$busca'", $link);

$AboA = mysql_query("SELECT * FROM cja WHERE orden='$busca'");
$Abo = mysql_fetch_array($AboA);

//************** INSTITUCION  ************************

echo "<table align='center' width='100%' border='1' cellpadding='0' cellspacing='0'>";

echo "<tr bgcolor='#eff2f9' height='40'>";
echo "<td width='10%' align='left' >";
echo "$Gfont <font size='2'> Institucion: </font>";
echo "</td>";
$InsA = mysql_query("SELECT institucion,nombre,alias,descuento,condiciones FROM inst where institucion='$Ot[institucion]'");
$Ins  = mysql_fetch_array($InsA);
echo "<td width='35%'>$Gfont<font color='#000099' size=2><b><a href=javascript:winuni('instobs.php?busca=$Ot[inst]')>$Ot[institucion] - $Ins[nombre]</a></b></font></td>";
echo "<td>";
echo "$Gfont&nbsp; Lista: $Ot[lista]";
$InsC = mysql_query("SELECT condiciones,mail,msjadministrativo FROM inst where institucion=$Ot[institucion]");
$Ins2=mysql_fetch_array($InsC);
if ($Ins2[condiciones]=='CONTADO'){
	echo "$Gfont<font color='#000099' size=-1> &nbsp; $Ins2[condiciones]";
}else{
	echo "$Gfont<font color='#CC0000' size=-1> &nbsp; $Ins2[condiciones]";
}
echo "</font>$Gfont &nbsp; $Ins2[mail]";
echo "</form>";
echo "</td>";

echo "<td width='15%' align='center' colspan='2'>";
echo "<form name='form8' method='post' action='ordenesnvasNe.php?pagina=$pagina&busca=$busca&op=Sv&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
if($Ot[servicio]==""){$DisSer="Ordinaria";}else{$DisSer=$Ot[servicio];}
echo "$Gfont Servicio: ";
echo "<select size='1' name='Servicio' class='Estilo10' onchange=this.form.submit()>";
echo "<option value='Ordinaria'>Ordinaria</option>";
echo "<option value='Urgente'>Urgente</option>";
echo "<option value='Express'>Express</option>";
echo "<option value='Hospitalizado'>Hospitalizado</option>";
echo "<option value='Nocturno'>Nocturno</option>";
echo "<option selected value='$DisSer'>$DisSer</option>";
echo "</select>";
echo "</form>";
echo "</td></div>";	
echo "</tr>";
  
  //************** PACIENTE  ************************
echo "<tr height='40'>";
echo "<td align='left'>";
echo "$Gfont Paciente: ";
echo "</td>";
echo "<td>";
//echo "<form name='form3' method='post' action='ordenesnvasN.php?op=cl&Vta=$Vta'>";
$nPrg=$Cli[programa];
echo "<a class='vt' href=javascript:winuni('Upcliente.php?busca=$Ot[cliente]') title='Click para actualizar sus datos'>$Gfont<font color='#000099' size=2><b>$Ot[cliente] - ".strtoupper(substr($Cli[nombrec],0,35))."</b></a>";
echo "</td>";
echo "<td colspan='3'>";

if($Cli[observaciones]<>''){
	echo "<a href=javascript:winuni('cliobs.php?busca=$Ot[cliente]')><img src='lib/int4.gif' border='0' width='20' height='20' align='middle'></a>";
}
echo "/ Col. ".ucwords(strtolower($Cli[colonia])) . " " . ucwords(strtolower($Cli[municipio]));
echo "</font>$Gfont &nbsp; E-mail: $Cli[mail] ";
echo " &nbsp; Programa: $aPrg[$nPrg] &nbsp;";
echo "$Gfont vecs: <font color=#FF0000 size=+2><b><a class='vt' href=javascript:winuni('repots.php?busca=$Ot[cliente]')>".$Cli[numveces]."</b></a>";
echo "</font></form>";
echo "</td>";
echo "</tr>";


//************** MEDICO  ************************

echo "<tr bgcolor='#eff2f9'>";
echo "<td>";
echo "$Gfont Medico: ";
echo "</td>";

echo "<td>";
$Medico2 = $Ot[medico];
if($Medico2=='MD'){$nommed=$Ot[medicon];}else{$nommed=substr($Med[nombrec],0,45);}
echo "$Gfont<font color='#000099' size=2><b><a href=javascript:winuni('medicobs.php?orden=$Ot[medico]')>".$Med[medico]." - ".$nommed."</a></b>";
echo "</td>";
echo "<td>";

echo "</font> &nbsp; E-mail: $Med[mail]";
echo " &nbsp; Clasif.: <font color=#FF0000 size=+1> $Med[clasificacion] &nbsp; </font>";
echo "</td>";

echo "<td align='center' colspan='2'>";

echo "<form name='form4' method='post' action='ordenesnvasNe.php?op=rec&pagina=$pagina&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><font size='-1'>$Gfont";
echo "$Gfont No.de receta o folio alterno: ";
echo "<input class='textos' name='Receta' type='text' size='10' value ='$Ot[receta]'>";
echo "</form>"; 
echo "</td>";

echo "</tr>";

//************** ESTUDIO  ************************

echo "<tr>";
echo "<td>";
echo "$Gfont Orden: ";
echo "</td>";
echo "<td>";

echo "$Gfont<font color='#000099' size=2><b>$busca</b> &nbsp; &nbsp;  &nbsp; &nbsp;  &nbsp; &nbsp; <a  href='envioest.php?pagina=$pagina&Sort=Asc&busca=$busca&orden=ot.orden&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><font color='#1e8449' size=2><b> REGRESAR </b></a></font>";

echo "</td>";

echo "<td>";
echo "<form name='form4' method='post' action='ordenesnvasNe.php?op=dato&pagina=$pagina&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
echo "$Gfont Dato complementario:<input class='textos' name='Datoc' type='text' size='35' value ='$Ot[datoc]'>";
echo "</form>";
echo "</td>";

echo "<td align='center'>";
echo "<form name='form4' method='post' action='ordenesnvasNe.php?op=Fc&pagina=$pagina&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
echo "$Gfont Fech/entrega:<input class='textos' type='text' name='Fechae' value ='$Ot[fechae]' maxlength='10' size='12'>";
echo "</form>";
echo "</td>";
echo "<td>";
echo "<form name='form4' method='post' action='ordenesnvasNe.php?op=Fc1&pagina=$pagina&busca=$busca&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
echo "<input class='textos' type='text' name='Horae' value ='$Ot[horae]' maxlength='8' size='6'>";
echo "</form>";

echo "</td>";
echo "</tr>";
echo "</table>";
echo "<hr>";

//***********  ENTREGA SUCURSALES ********

if($Ot[tentregamost]==2){
  $Matriz="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=1&tentregamost=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasN.php?busca=$busca&op=most&Sucent=2&tentregamost=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=3&tentregamost=3&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=4&tentregamost=4&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Los Reyes</a>";
}elseif($Ot[tentregamost]==3){
  $Matriz="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=1&tentregamost=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=2&tentregamost=2&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=3&tentregamost=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=4&tentregamost=4&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Los Reyes</a>";
}elseif($Ot[tentregamost]==4){
  $Matriz="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=1&tentregamost=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=2&tentregamost=2&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=3&tentregamost=3&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=4&tentregamost=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>Los Reyes</a>";
}elseif($Ot[tentregamost]==1){
  $Matriz="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=1&tentregamost=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=2&tentregamost=2&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=3&tentregamost=3&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasNe.php?busca=$busca&Sucent=4&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Los Reyes</a>";
}elseif($Ot[tentregamost]==0){
  $Matriz="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=1&tentregamost=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=2&tentregamost=2&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=3&tentregamost=3&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=most&Sucent=4&tentregamost=4&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Los Reyes</a>";
}

//***********  ENTREGA DOMICILIO  ********

if($Ot[tentregamed]==1){
  $Particular="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=2&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=3&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Institucion</a>";
}elseif($Ot[tentregamed]==2){
  $Particular="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=3&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Institucion</a>";
}elseif($Ot[tentregamed]==3){
  $Particular="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=2&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>Institucion</a>";
}else{
  $Particular="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=2&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=medi&tentregamed=3&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>Institucion</a>";
}

//***********  ENTREGA DIGITAL  ********

if($Ot[entemailpac]==1){
  $seleccionmp="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=0&entemailpac=0&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>";
}else{
  $seleccionmp="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=1&entemailpac=1&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
}

if($Ot[entemailmed]==1){
  $seleccionmm="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=0&entemailpac=$Ot[entemailpac]&entemailmed=0&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>";
}else{
  $seleccionmm="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=3&entemailpac=$Ot[entemailpac]&entemailmed=1&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
}

if($Ot[entemailinst]==1){
  $seleccionmi="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=0&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=0&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>";
}else{
  $seleccionmi="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=5&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=1&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
}

if($Ot[entwhatpac]==1){
    $seleccionwp="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=2&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=0&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>";
}else{
    $seleccionwp="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=2&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=1&entwhatmed=$Ot[entwhatmed]&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
}

if($Ot[entwhatmed]==1){
    $seleccionwm="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=4&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=0&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'><img src='lib/slc.png' width='15'>";
}else{
    $seleccionwm="<a class='vt' href='ordenesnvasNe.php?busca=$busca&op=digi&tentregadig=4&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=1&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
}

  $Emailpac=$seleccionmp."Mail/Pac</a>";
  $Whatpac=$seleccionwp."Whats/Pac</a>";
  $Emailmed=$seleccionmm."Mail/Med</a>";
  $Whatmed=$seleccionwm."Whats/Med</a>";
  $Emailinst=$seleccionmi."Mail/Inst</a>";

//***********  TIPO DE ENTREGA  ********

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";

echo "<tr>";

echo "<td align='left' width='7%'>$Gfont <b><font size=1>ENTREGA --></b></td>";

echo "<td align='left' width='31%'>$Gfont <font color=#17202a size=1><u>MOSTRADOR:</u> &nbsp; &nbsp; ".$Matriz."   &nbsp; &nbsp; ".$Futura."  &nbsp; &nbsp; ".$Tepexpan."   &nbsp; &nbsp; ".$Reyes."</td>";

echo "<td align='left' width='24%'>$Gfont <font color=#17202a size=1><u>DOMICILIO:</u> &nbsp; &nbsp; ".$Particular."   &nbsp; &nbsp; ".$Dmedico."  &nbsp; &nbsp; ".$Dinstitucion."</td>";

echo "<td align='left' width='38%'>$Gfont <font color=#17202a size=1><u>DIGITAL:</u> &nbsp; &nbsp;".$Emailpac."   &nbsp; &nbsp;".$Whatpac." &nbsp; &nbsp;".$Emailmed." &nbsp; &nbsp;".$Whatmed." &nbsp; &nbsp;".$Emailinst."</td>";

echo "</tr>"; 
echo "</table>";

echo "<hr>";

if(!$res=mysql_query($cSql.$cWhe,$link)){

	cMensaje("No se encontraron resultados � hay un error en el filtro");    #Manda mensaje de datos no existentes

}else{

	CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

	if($limitInf < 0){$limitInf=0;}

	$sql = $cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

	$res = mysql_query($sql,$link);

	//PonEncabezado();         #---------------------Encabezado del browse----------------------
  echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='1'>";
  echo "<tr align='center'>";
  echo "<td>$Gfont <b>Estudio</b></td>";
  echo "<td>$Gfont <b>Descripcion</b></td>";
  echo "<td>$Gfont <b>Precio</b></td>";
  echo "<td>$Gfont <b>Movto</b></td>";
  echo "<td>$Gfont <b>Importe</b></td>";
  echo "<td>$Gfont <b>Elim</b></td>";
  echo "<td>$Gfont <b>Fec/Entrega</b></td>";
  echo "</tr>";

	while($registro=mysql_fetch_array($res)){

		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

		echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		$estud = strtolower($registro[estudio]);            
		echo "<td align='left'><a href=javascript:wingral('resultapdf.php?Estudio=$registro[estudio]&alterno=0')><font size='-1'><img src='pdfenv.png' alt='pdf' border='0' width='15' height='15'/></font></a>$Gfont<a href=javascript:winuni('estudiosobs.php?estudio=$registro[estudio]')>$registro[estudio]</a></td>";
		echo "<td>$Gfont <font size='-2'>$registro[descripcion]</font></td>";
		echo "<td align='right'>$Gfont ".number_format($registro[precio],"2")."</td>";
		echo "<td align='center'>$Gfont $registro[descuento]</td>";
		echo "<td align='right'>$Gfont ".number_format($registro[precio]*(1-$registro[descuento]/100),"2")."</td>";
		echo "<td align='center'>$Gfont </td>";
		echo "<td align='center'>$Gfont $registro[fechaest]</td>";
		echo "</tr>";
		$nImp=$nImp+($registro[precio]*(1-($registro[descuento]/100)));
		$nRng++;
	}

	PonPaginacion4(false);      #-------------------pon los No.de paginas-------------------

}//fin if

echo "<table border='0' width='100%' align='center' border=0 cellpadding=0 cellspacing=0>";

echo "<tr>";
echo "<td width='50%' align='left'>";
echo "<table width='100%' border='0'>";
	  
echo "<form name='form4' method='post' action='ordenesnvasNe.php?busca=$busca&op=do&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'> ";
echo "<tr>";
echo "<td align='center' valign=top>$Gfont Diagnostico Medico:</td>"; 
echo "<td><TEXTAREA NAME='Diagmedico' cols='60' rows='2'>$Ot[diagmedico]</TEXTAREA></td>";
echo "</tr>";
	  
echo "<tr>";
echo "<td align='center' valign=top>$Gfont Observaciones: <input class='textos' type='submit' name='Submit' value='Enviar'></td>"; 
echo "<td><TEXTAREA NAME='Observaciones' cols='60' rows='2'>$Ot[observaciones]</TEXTAREA>";
echo "</td>";
echo "</tr>"; 
echo "</table></td>";
echo "</form>";

echo "<td width='50%' align='left'>";
echo "<table width='100%' border='0'>";

echo "<tr>";
echo "<td align='left'>$Gfont<b>Importe: $ </td>";
echo "<td align='left'>$Gfont".number_format($He[0],'2')."</b></td>";
echo "<td align='center'>$Gfont<b>T/Pago:</td>";
echo "<td align='center'>$Gfont<b>Fec.Receta:</td>";
echo "</tr>";

echo "<tr>";
echo "<td align='left'>$Gfont<b>Abono: $ </td>";
echo "<td align='left'>$Gfont".number_format($Abo[importe],'2')."</td>";

echo "<td align='center'>$Gfont $Abo[tpago]</td>";
echo "<td align='center'>";

echo "<form name='form4' method='post' action='ordenesnvasNe.php?busca=$busca&op=frec&pagina=$pagina&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9'>";
echo "$Gfont <input class='textos' name='Fechar' type='text' size='10' value='$Ot[fecharec]'>";
echo "</form>";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td align='left'>$Gfont<b>Resta: $ </td>";
echo "<td align='left'>$Gfont".number_format($He[0]-$Abo[importe],'2')."</b></td>";
echo "<td align='left'>$Gfont</td>";
echo "</tr>";

echo "<tr>";

echo "<td align='center'><a class='vt' href=javascript:wingral('impotpdf.php?busca=$busca')>Reimpresion de OT</a></td>";
echo "<td align='center'><a class='vt' href=javascript:wingral('cotizacion.php?Vta=$Vta')>Cotizacion</a></td>";
echo "<td align='center'><a class='vt' href=javascript:wingral('impos.php')>Servicio a domicilio</a></td>";

echo "</tr>";

echo "</table></td>";
echo "</tr>";

echo "</td>";
echo "</tr>";

echo "</table>";

echo "</tr>";
echo "</table>";

mysql_close();

echo "</body>";

echo "</html>";

?>
<style type='text/css'>

a.vt:link {

 color: #003c72;

    font-size: 11px;

    text-decoration: none;

}

a.vt:visited {

    color: #003c72;

    font-size: 14px;

    text-decoration: none;

}

a.vt:hover {

    color: #111111;

    font-size: 14px;

}

</style>

