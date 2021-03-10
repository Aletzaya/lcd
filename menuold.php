<?
require("aut_verifica.inc.php");
$nivel_acceso=10; // Nivel de acceso para esta página.
if ($nivel_acceso < $HTTP_SESSION_VARS['usuario_nivel']){
   header ("Location: $redir?error_login=5");
   exit;
}
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
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1">
<script type="text/javascript" language="JavaScript1.2" src="stm31.js"></script>
<script language="JavaScript" type="text/JavaScript">
var size = 20;
var speed_between_messages=7500  //en milisegundos

var tekst = new Array()
{
tekst[0] = "Bienvenido";
tekst[1] = "Sistema clinico LCD";
}
var klaar = 0;
var s = 0;
var veran =0;
var tel = 0;
function bereken(i,Lengte)
{
return (size*Math.abs( Math.sin(i/(Lengte/3.14))) );
}

function motor(p)
{
var output = "";
for(w = 0;w < tekst[s].length - klaar+1; w++)
{
q = bereken(w/2 + p,16);
if (q > size - 0.5)
{klaar++;}
if (q < 5)
{tel++;
if (tel > 1)
{
tel = 0;
if (veran == 1)
{
veran = 0;
s++;
if ( s == tekst.length)
{s = 0;}
p = 0;
if (window.loop)
{clearInterval(loop)}
loop = motor();
}
}
}
output += "<font style='font-size: "+ q +"pt'>" +tekst[s].substring(w,w+1)+ "</font>";
}
for(k=w;k<klaar+w;k++)
{
output += "<font style='font-size: " + size + "pt'>" +tekst[s].substring(k,k+1)+ "</font>";
}
idee.innerHTML = output;
}

function startmotor(p){
if (!document.all)
return
var loop = motor(p);
if (window.time)
{clearInterval(time)}
if (klaar == tekst[s].length)
{
klaar = 0;
veran = 1;
tel = 0;
var time = setTimeout("startmotor(" +(p+1) + ")", speed_between_messages);
}else
{
var time =setTimeout("startmotor(" +(p+1) + ")", 50);
}
}

function gothere(mname)
{
var thisform = mname;
 if (thisform.selectsite.options[thisform.selectsite.options.selectedIndex].value != "nolink") {location.href=thisform.selectsite.options[thisform.selectsite.options.selectedIndex].value;}
}
<!--
function MM_reloadPage(init) {  //reloads the window if Nav4 resized
  if (init==true) with (navigator) {if ((appName=="Netscape")&&(parseInt(appVersion)==4)) {
    document.MM_pgW=innerWidth; document.MM_pgH=innerHeight; onresize=MM_reloadPage; }}
  else if (innerWidth!=document.MM_pgW || innerHeight!=document.MM_pgH) location.reload();
}
MM_reloadPage(true);
//-->
</script>
</head>
<body bgcolor="#FFFFFF" onload="startmotor(0)">
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
    <td width="82%" bgcolor="#6633ff"> <strong><font color="#FFFFFF" size="2" face="Verdana, Arial, Helvetica, sans-serif"><img src="imagenes/dot.gif" alt="Sistema Clinico Duran(FroylanAya@hotmail.com Ver 1.2)" width="15" height="16">&nbsp;<?php echo $Titulo;?></font></strong>
    </td>
    <td width="18%" height="23"><div align="right"><script type="text/javascript" language="JavaScript1.2">
<!--
stm_bm(["menu4bb6",400,"","blank.gif",0,"","",0,0,19,19,144,1,0,0,""],this);
stm_bp("p0",[0,4,0,0,0,3,0,7,100,"",-2,"",-2,90,0,0,"#006699","#ffffff","",3,0,0,"#ffffff"]);
stm_ai("p0i0",[0,"Recepcion","","",-1,-1,0,"","_self","","","","",0,0,0,"arrow_r.gif","arrow_r.gif",7,7,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","bold 8pt Arial","bold 8pt Arial",0,0]);
stm_bp("p1",[1,4,0,0,0,3,0,0,100,"progid:DXImageTransform.Microsoft.Checkerboard(squaresX=12,squaresY=12,direction=down,enabled=0,Duration=0.25)",11,"",-2,85,0,0,"#7f7f7f","transparent","",3,0,0,"#000000"]);
stm_aix("p1i0","p0i0",[0,"Ordenes de trabajo","","",-1,-1,0,"ordenes.php","_self","","","","",0,0,0,"","",0,0,0,0,1,"#6633ff",0,"#ffffff",0,"","",3,3,1,2,"#ffffff #000066 #00cc33 #ffffff","#ffffff #ffffff #009933 #ffffff","#ffffff","#666666","8pt Arial","8pt Arial"]);
stm_aix("p1i1","p1i0",[0,"Ingresos","","",-1,-1,0,"ingresos.php"]);
stm_aix("p1i2","p1i0",[0,"Corte de caja","","",-1,-1,0,"corte.php"]);
stm_ep();
stm_aix("p0i1","p0i0",[0,"Catalogos"]);
stm_bpx("p2","p1",[]);
stm_aix("p2i0","p1i0",[0,"Pacientes","","",-1,-1,0,"clientes.php"]);
stm_aix("p2i1","p1i0",[0,"Medico","","",-1,-1,0,"medicos.php"]);
stm_aix("p2i2","p1i0",[0,"Estudios","","",-1,-1,0,"estudios.php"]);
stm_aix("p2i3","p1i0",[0,"Zonas","","",-1,-1,0,"zonas.php"]);
stm_aix("p2i4","p1i0",[0,"Instituciones","","",-1,-1,0,"institu.php"]);
stm_aix("p2i5","p1i0",[0,"Lista de precios","","",-1,-1,0,"lista.php"]);
stm_aix("p2i6","p1i0",[0,"Estudios por institucion"]);
stm_aix("p2i7","p1i0",[0,"Departamentos","","",-1,-1,0,"depto.php"]);
stm_aix("p2i8","p1i0",[0,"Cuestionario Pre-analitico","","",-1,-1,0,"preguntas.php"]);
stm_aix("p2i9","p1i0",[0,"Cuetionario por Est.","","",-1,-1,0,"cuepre.php"]);
stm_ep();
stm_aix("p0i2","p0i0",[0,"Pre-analiticos"]);
stm_bpx("p3","p1",[]);
stm_aix("p3i0","p1i0",[0,"Pre-analiticos"]);
stm_ep();
stm_aix("p0i3","p0i0",[0,"Captura de resultados"]);
stm_bpx("p4","p1",[]);
stm_aix("p4i0","p1i0",[0,"Ordenes en Pre-analiticos","","",-1,-1,0,"ordenespre.php"]);
stm_aix("p4i1","p1i0",[0,"Estudios por depto","","",-1,-1,0,"estdepto.php"]);
stm_aix("p4i2","p1i0",[0,"Resultados","","",-1,-1,0,"resultados.php"]);
stm_ep();
stm_aix("p0i4","p0i0",[0,"Reportes","","",-1,-1,0,"Reportes.php"]);
stm_bpx("p5","p1",[]);
stm_aix("p5i0","p1i0",[0,"Reportes","","",-1,-1,0,"reportes.php"]);
stm_aix("p5i0","p1i0",[0,"Administracion Usuarios","","",-1,-1,0,"aut_gestion_usuarios.php"]);
stm_ep();
stm_ep();
stm_em();
//-->
</script>
</div></td></tr>
</table>
<hr noshade style="color:3366FF;height:2px">
<table width="98%" border="0">
  <tr>
    <td><p align="center">
      <div align="center" ID="idee"></div>
    </td>
  </tr>
</table>

<p align="center"><img src="imagenes/logo1.jpg" width="400" height="200"></p>
<p>&nbsp;</p>
<hr noshade style="color:66CC66;height:3px">
<td width="416" valign="top">
</td>
</body>
</html>