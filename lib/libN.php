<?php

function conectarse(){

    require '../lcd/lib/dbSettings.php';
    
  if(!($link=mysql_connect("localhost","$_SESSION[usr]","det15a"))){
    echo "Error conectando a la base de datos.";
    exit();
  }
  if(!mysql_select_db("lcd",$link)){
    echo "Error seleccionando la base de datos.";
    exit();
  }

        date_default_timezone_set("America/Mexico_City");

  return $link;

}


function conect() {

    date_default_timezone_set("America/Mexico_City");

    require '../lcd/lib/dbSettings.php';

    if (!($link = mysqli_connect($dbhost, $dbusername, $dbpass, $dbname, $dbport, $dbsocket))) {
        echo "Error conectando a la base de datos.";
        mysqli_error();
        exit();
    }
    mysqli_set_charset($link, $dbcharset);

    return $link;
}

function cMensaje($Mensaje){
 		echo "<div align='center'>";

    	echo "<font face='verdana' size='2'>$Mensaje</font>";

        echo "<p align='center'><a class='ord' href='$_SERVER[PHP_SELF]?op=br'>";

        echo "Refresca pantalla.</a></p>";

 		echo "</div>";
return true;

}

function cZeros($Vlr,$nLen){   // Function p/ rellenar de zeros
  for($i = strlen($Vlr); $i < $nLen; $i=$i+1){
      $Vlr = "0".$Vlr;
   }
  return $Vlr;
}

function headymenu($Titulo,$Disp){	//Disp si es true desplega el menu

global $Tda,$Id;


require ('config.php');

session_start();

$Fec    = date('Y-m-d');
$Usr    = $_COOKIE['USERNAME'];
$Niv    = $_COOKIE['LEVEL'];
$Suc    = $_COOKIE['TEAM'];        //Sucursal 

$CiaA   = mysql_query("SELECT nombre FROM cia WHERE id='$Suc'");
$Cia    = mysql_fetch_array($CiaA);

$MsA    = mysql_query("SELECT count(*) FROM msj WHERE (para='$Usr' OR para='$Tda') AND !bd");
$Ms     = mysql_fetch_array($MsA);
$nMsj   = $Ms[0];


echo "<script language='JavaScript1.2'>";
echo "function confirmar( mensaje, destino) { ";
echo "if (confirm(mensaje)) {";
echo "  document.location = destino ;";
echo "}";
echo "}</script>";

$Mnu="menu/lcd".$Niv.".js";

//echo "Menu $Mnu";

echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=0 bgcolor='#FFFFFF'>";

echo "<tr>";

echo "<td width='200'><a href='menu.php'><img  src='lib/logo2.gif' border='0'></a></td>";

echo "<td align='left' valign='bottom'>$Gfont <font size='+1'><b>$Cia[nombre]</td>";

echo "<td align='right' valign='bottom'>$Gfont ";

//echo " <font size='-1'> $Fec | <a class='pg' href='menu.php'> Inicio </a> | <a class='pg' href=javascript:winuni('ayuda.php?Id=$Id')>Ayuda </a> &nbsp;| <a class='pg' href='logout.php'><b> Salir </b></a></font>";

echo " &nbsp; </td>";

echo "<td width=91><img  src='lib/logo40.jpg' border='0'></td>";

echo "</tr>";

echo "</table>";

echo "<table width='100%' cellpadding='3' cellspacing='0' border='0'><tr>";
if($Disp){
   echo "<td height='22'><script type='text/JavaScript' src='$Mnu'></script></td>";
}else{
   echo "<td height='22'><script type='text/JavaScript' src='$Mnu'></script></td>";
}
echo "</tr></table>";


/*
echo "<table width='100%' cellpadding=0 cellspacing=0 border='0'>";

echo "<tr bgcolor='$GfdoTitulo'>";

echo "<td height='22'> <script language='JavaScript1.2'>generate_mainitems()</script></td>";

echo "</tr></table>";
*/
//echo "<br>";

echo "<table align='center' width='100%' cellpadding='0' cellspacing='0' border='0'>";

//echo "<tr><td>$Gfont <br><img src='lib/box.gif' border='0'> <font color='#69b747' size='+2'>$Titulo</td>";
echo "<tr><td>$Gfont <br><font color='#69b747' size='+1'> <b> <img src='lib/0006.gif' border='0'> $Titulo</td>";

echo "<td align='right'>$Gfont <br><b> ";

echo " <img src='lib/msjn.png' border='0'> $Usr</b> &nbsp; ";
echo " | Sin leer <a class='pg' href=javascript:winuni('msjrec.php')><font color='#69b747'><b> $nMsj msj(s)</b></font></a>";
echo " | <a class='pg' href=javascript:winuni('msjenve.php?busca=NUEVO')> Nvo.msj</a>";
echo " | <a class='pg' href='logout.php'><b> S a l i r</b></a><img src='lib/exit.png' border='0'> ";

echo "&nbsp;</td>";

echo "</tr></table>";

return true;

}


function headymenu2($Titulo,$Disp){  //Disp si es true desplega el menu

global $Tda,$Id;


require ('config.php');

session_start();

$Fec    = date('Y-m-d');
$Usr    = $_COOKIE['USERNAME'];
$Niv    = $_COOKIE['LEVEL'];
$Suc    = $_COOKIE['TEAM'];        //Sucursal 

$CiaA   = mysql_query("SELECT nombre FROM cia WHERE id='$Suc'");
$Cia    = mysql_fetch_array($CiaA);

$MsA    = mysql_query("SELECT count(*) FROM msj WHERE (para='$Usr' OR para='$Tda') AND !bd");
$Ms     = mysql_fetch_array($MsA);
$nMsj   = $Ms[0];


echo "<script language='JavaScript1.2'>";
echo "function confirmar( mensaje, destino) { ";
echo "if (confirm(mensaje)) {";
echo "  document.location = destino ;";
echo "}";
echo "}</script>";

$Mnu="menu/lcd".$Niv.".js";

//echo "Menu $Mnu";

echo "<table width='99%' align='center' border=0 cellpadding=0 cellspacing=0 bgcolor='#FFFFFF'>";

echo "<tr>";

echo "<td width='200'><a href='menu.php'><img  src='lib/logo2.gif' border='0'></a></td>";

echo "<td align='left' valign='bottom'>$Gfont <font size='+1'><b>$Cia[nombre]</td>";

echo "<td align='right' valign='bottom'>$Gfont ";

echo " &nbsp; </td>";

echo "<td width=91><img  src='lib/logo40.jpg' border='0'></td>";

echo "</tr>";

echo "</table>";

echo "<table width='100%' cellpadding='3' cellspacing='0' border='0'><tr>";
if($Disp){
   echo "<td height='22'><script type='text/JavaScript' src='$Mnu'></script></td>";
}else{
   echo "<td height='22'><script type='text/JavaScript' src='$Mnu'></script></td>";
}
echo "</tr></table>";

return true;

}


function PonEncabezado(){
require ('config.php');
global $numeroRegistros,$orden,$aIzq,$aDat,$aDer,$aCps,$cFuncion,$Sort;   #P k reconoscan el valor k traen
//global $pagina,$numeroRegistros,$orden,$busca,$aIzq,$aDat,$aDer,$aCps,$cFuncion,$Sort;   #P k reconoscan el valor k traen

      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='1'><tr>";

	  for ($i = 0; $i < sizeof($aIzq); $i=$i+3) {
            echo "<th height='26' background='lib/bartit.gif'><font color='#003399' size='1'>$aIzq[$i]</font></font></th>";
	  }
	  $x=0;
	  for ($i = 0; $i < sizeof($aDat); $i=$i+3) {
	       $Pso=$aCps[$x];
	       if($orden==$aCps[$x]){			//Es el campo por el cual esta en este momento ordenado;
	          if($Sort=='Asc'){$Srt='Desc';$iImg='asc.png';}else{$Srt='Asc';$iImg='des.png';}
             //echo "<th background='lib/bartit.gif'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=$Pso&Sort=$Srt'>$aDat[$i] <img src='lib/$iImg' border='0'> </a></th>";
             echo "<th background='lib/bartit.gif'><a class='pg' href='".$_SERVER["PHP_SELF"]."?orden=$Pso&Sort=$Srt&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'>$aDat[$i] <img src='lib/$iImg' border='0'> </a></th>";

	       }else{
             echo "<th background='lib/bartit.gif'><a class='pg' href='".$_SERVER["PHP_SELF"]."?&orden=$Pso&Sort=Asc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'>$aDat[$i] </a></th>";
	       }
          $x++;
	  }
	  for ($i = 0; $i < sizeof($aDer); $i=$i+3) {
          echo "<th background='lib/bartit.gif'><font color='#003399' size='1'> $aDer[$i] </th>";
	  }

      echo "</tr>";

	  return true;
}

function PonEncabezado2(){
require ('config.php');
global $numeroRegistros,$orden,$aIzq,$aDat,$aDer,$aCps,$cFuncion,$Sort,$estudio;   #P k reconoscan el valor k traen
//global $pagina,$numeroRegistros,$orden,$busca,$aIzq,$aDat,$aDer,$aCps,$cFuncion,$Sort;   #P k reconoscan el valor k traen

      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='1'><tr>";

    for ($i = 0; $i < sizeof($aIzq); $i=$i+3) {
            echo "<th height='26' background='lib/bartit.gif'><font color='#003399' size='1'>$aIzq[$i]</font></font></th>";
    }
    $x=0;
    for ($i = 0; $i < sizeof($aDat); $i=$i+3) {
         $Pso=$aCps[$x];
         if($orden==$aCps[$x]){     //Es el campo por el cual esta en este momento ordenado;
            if($Sort=='Asc'){$Srt='Desc';$iImg='asc.png';}else{$Srt='Asc';$iImg='des.png';}
             //echo "<th background='lib/bartit.gif'><a class='ord' href='".$_SERVER["PHP_SELF"]."?pagina=".$pagina."&busca=".$busca."&orden=$Pso&Sort=$Srt'>$aDat[$i] <img src='lib/$iImg' border='0'> </a></th>";
             echo "<th background='lib/bartit.gif'><a class='pg' href='".$_SERVER["PHP_SELF"]."?orden=$Pso&Sort=$Srt&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&busca=$estudio'>$aDat[$i] <img src='lib/$iImg' border='0'> </a></th>";

         }else{
             echo "<th background='lib/bartit.gif'><a class='pg' href='".$_SERVER["PHP_SELF"]."?&orden=$Pso&Sort=Asc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&busca=$estudio'>$aDat[$i] </a></th>";
         }
          $x++;
    }
    for ($i = 0; $i < sizeof($aDer); $i=$i+3) {
          echo "<th background='lib/bartit.gif'><font color='#003399' size='1'> $aDer[$i] </th>";
    }

      echo "</tr>";

    return true;
}


function PonPaginacion($Bd){
global $inicio,$Msj,$cSql,$pagina,$tamPag,$orden,$numPags,$final,$busca,$nRng,$Sort,$Id,$numeroRegistros,$Vta;
#P k reconoscan el valor k traen

require ('config.php');

		if(sizeof($Bd)>1){
			$Comodin = $Bd[1];
			$Bd      = $Bd[0];
		}

      for ($i = $nRng; $i < $tamPag-2; $i++) {
          echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
      }

      echo "</table>";


		//Mensajes y No de registros;
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'><tr height='25' >";

      //echo "<td width='50%' bgcolor='#b6b6b6'>$Gfont <b> &nbsp; <font color='#ffffff'> $Msj </font></b></td>";
      echo "<td width='60%'>$Gfont <font color='#003399'> $Msj </td>";

      echo "<td width='40%' align='right'>$Gfont Registros: ".number_format($numeroRegistros,"0")."&nbsp;</td>";

      echo "</tr></table>";

            //echo $cSql;


      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cSql   = str_replace("'","!",$cSql);                      //Remplazo la comita p'k mande todo el string
      //$cSql   = str_replace("*","|",$cSql);                      //Remplazo la comita p'k mande todo el string
      $Sql    = str_replace("+","~",$cSql);                      //Remplazo la comita p'k mande todo el string

      
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

      echo "<tr><td height='40'><b>";

      if($Bd){

         echo " <a class='pg' href='$cLink?busca=NUEVO&Vta=$Vta'>Agrega</a> &nbsp; ";

         echo "<a class='pg' href='bajarep.php?cSql=$Sql'> Exporta </a> &nbsp; ";

         echo "$Comodin  &nbsp; ";

         echo "<a class='pg' href=javascript:winuni('filcampos.php?Id=$Id')>Filtro</a> &nbsp;&nbsp; ";

         echo "<a class='pg' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a> &nbsp;&nbsp; ";

         echo "<img src='lib/print.png' alt='Imprimir pantalla' border='0' onClick='window.print()'>";

     }

     echo "</b></td><td align='right' valign='bottom'>$Gfont ";
     echo "<td align='right' valign='bottom'>$Gfont ";

     if($numPags <= 10 ){

        for($i=1;$i<=$numPags;$i++)    {

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $i ]</b></font> ";

            }else{

               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$i&Vta=$Vta'>&nbsp; $i &nbsp;</a>";

            }
        }

     }else{

		  $ini = 1;
		  if($pagina >= 7){

		     $ini = $pagina - 5;

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=1&Vta=$Vta'><img src='lib/imini.gif' border='0'></a>&nbsp;";

			  if($pagina-11>=1){

			      if($pagina >= $numPags - 3){
			         $pg = $ini - 9;
			      }else{
                  $pg = $pagina - 11;
               }
               echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$pg&Vta=$Vta'><img src='lib/imant.gif' border='0'></a>&nbsp; ";

			  }

		     if($ini+10>$numPags){$ini = $numPags - 10;}

		  }

		  $fin = $ini + 10;

        for($i=$ini;$i<=$fin;$i++)    {

   			$pag = cZeros($i,2);

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $pag ]</b></font>&nbsp;";

            }else{
               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$i&Vta=$Vta'> $pag &nbsp; </a>";

            }

        }

		  if($pagina+11 <= $numPags){

           $pg = $pagina + 11;
           echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$pg&Vta=$Vta'><img src='lib/imsig.gif' border='0'></a>&nbsp; ";

		  }

		  if($pagina < ($numPags-5)){

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$numPags&Vta=$Vta'><img src='lib/imfin.gif' border='0'></a>&nbsp;";

		  }

     }

     echo " &nbsp; </td></tr></table>";

return true;

}

function PonPaginacion2($Bd){
global $inicio,$Msj,$cSql,$pagina,$tamPag,$orden,$numPags,$final,$busca,$nRng,$Sort,$Id,$numeroRegistros,$Depto,$Subdepto,$Suc;
#P k reconoscan el valor k traen

require ('config.php');

		if(sizeof($Bd)>1){
			$Comodin = $Bd[1];
			$Bd      = $Bd[0];
		}

      for ($i = $nRng; $i < $tamPag-2; $i++) {
          echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
      }

      echo "</table>";


		//Mensajes y No de registros;
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'><tr height='25' >";

      //echo "<td width='50%' bgcolor='#b6b6b6'>$Gfont <b> &nbsp; <font color='#ffffff'> $Msj </font></b></td>";
      echo "<td width='50%'>$Gfont <font color='#003399'> $Msj </td>";

      echo "<td width='40%' align='right'>$Gfont Registros: ".number_format($numeroRegistros,"0")."&nbsp;</td>";

      echo "</tr></table>";

            //echo $cSql;


      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cSql   = str_replace("'","!",$cSql);                      //Remplazo la comita p'k mande todo el string
      //$cSql   = str_replace("*","|",$cSql);                      //Remplazo la comita p'k mande todo el string
      $Sql    = str_replace("+","~",$cSql);                      //Remplazo la comita p'k mande todo el string

      
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

      echo "<tr><td height='40'><b>";

      if($Bd){

         echo " <a class='pg' href='$cLink?busca=NUEVO'>Agrega</a> &nbsp; ";

         echo "<a class='pg' href='bajarep.php?cSql=$Sql'> Exporta </a> &nbsp; ";

         echo "$Comodin  &nbsp; ";

         echo "<a class='pg' href=javascript:winuni('filcampos.php?Id=$Id')>Filtro</a> &nbsp;&nbsp; ";

         echo "<a class='pg' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a> &nbsp;&nbsp; ";

         echo "<img src='lib/print.png' alt='Imprimir pantalla' border='0' onClick='window.print()'>";

     }

     echo "</b></td><td align='right' valign='bottom'>$Gfont ";

     if($numPags <= 10 ){

        for($i=1;$i<=$numPags;$i++)    {

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $i ]</b></font> ";

            }else{

               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&busca=$busca&pagina=$i'>&nbsp; $i &nbsp;</a>";

            }
        }

     }else{

		  $ini = 1;
		  if($pagina >= 7){

		     $ini = $pagina - 5;

           echo "<a href='".$_SERVER["PHP_SELF"]."?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&busca=$busca&pagina=1'><img src='lib/imini.gif' border='0'></a>&nbsp;";

			  if($pagina-11>=1){

			      if($pagina >= $numPags - 3){
			         $pg = $ini - 9;
			      }else{
                  $pg = $pagina - 11;
               }
               echo " <a href='".$_SERVER["PHP_SELF"]."?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&busca=$busca&pagina=$pg'><img src='lib/imant.gif' border='0'></a>&nbsp; ";

			  }

		     if($ini+10>$numPags){$ini = $numPags - 10;}

		  }

		  $fin = $ini + 10;

        for($i=$ini;$i<=$fin;$i++)    {

   			$pag = cZeros($i,2);

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $pag ]</b></font>&nbsp;";

            }else{
               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&busca=$busca&pagina=$i'> $pag &nbsp; </a>";

            }

        }

		  if($pagina+11 <= $numPags){

           $pg = $pagina + 11;
           echo " <a href='".$_SERVER["PHP_SELF"]."?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&busca=$busca&pagina=$pg'><img src='lib/imsig.gif' border='0'></a>&nbsp; ";

		  }

		  if($pagina < ($numPags-5)){

           echo "<a href='".$_SERVER["PHP_SELF"]."?Depto=$Depto&Subdepto=$Subdepto&Suc=$Suc&busca=$busca&pagina=$numPags'><img src='lib/imfin.gif' border='0'></a>&nbsp;";

		  }

     }

     echo " &nbsp; </td></tr></table>";

return true;

}

function PonPaginacion3($Bd){
global $inicio,$Msj,$cSql,$pagina,$tamPag,$orden,$numPags,$final,$busca,$nRng,$Sort,$Id,$numeroRegistros,$filtro,$filtro3,$filtro5,$filtro7,$filtro9,$Vta;
#P k reconoscan el valor k traen

require ('config.php');

		if(sizeof($Bd)>1){
			$Comodin = $Bd[1];
			$Bd      = $Bd[0];
		}

      for ($i = $nRng; $i < $tamPag-2; $i++) {
          echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
      }

      echo "</table>";


		//Mensajes y No de registros;
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'><tr height='25' >";

      //echo "<td width='50%' bgcolor='#b6b6b6'>$Gfont <b> &nbsp; <font color='#ffffff'> $Msj </font></b></td>";
      echo "<td width='60%'>$Gfont <font color='#003399'> $Msj </td>";

      echo "<td width='40%' align='right'>$Gfont Registros: ".number_format($numeroRegistros,"0")."&nbsp;</td>";

      echo "</tr></table>";

            //echo $cSql;


      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cSql   = str_replace("'","!",$cSql);                      //Remplazo la comita p'k mande todo el string
      //$cSql   = str_replace("*","|",$cSql);                      //Remplazo la comita p'k mande todo el string
      $Sql    = str_replace("+","~",$cSql);                      //Remplazo la comita p'k mande todo el string

      
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

      echo "<tr><td height='40'><b>";

      if($Bd){

         echo " <a class='pg' href='$cLink?busca=NUEVO'>Agrega</a> &nbsp; ";

         echo "<a class='pg' href='bajarep.php?cSql=$Sql'> Exporta </a> &nbsp; ";

         echo "$Comodin  &nbsp; ";

         echo "<a class='pg' href=javascript:winuni('filcampos.php?Id=$Id')>Filtro</a> &nbsp;&nbsp; ";

         echo "<a class='pg' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a> &nbsp;&nbsp; ";

         echo "<img src='lib/print.png' alt='Imprimir pantalla' border='0' onClick='window.print()'>";

     }

     echo "</b></td><td align='right' valign='bottom'>$Gfont ";

     if($numPags <= 10 ){

        for($i=1;$i<=$numPags;$i++)    {

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $i ]</b></font> ";

            }else{

               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$i&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'>&nbsp; $i &nbsp;</a>";

            }
        }

     }else{

		  $ini = 1;
		  if($pagina >= 7){

		     $ini = $pagina - 5;

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=1&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'><img src='lib/imini.gif' border='0'></a>&nbsp;";

			  if($pagina-11>=1){

			      if($pagina >= $numPags - 3){
			         $pg = $ini - 9;
			      }else{
                  $pg = $pagina - 11;
               }
               echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$pg&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'><img src='lib/imant.gif' border='0'></a>&nbsp; ";

			  }

		     if($ini+10>$numPags){$ini = $numPags - 10;}

		  }

		  $fin = $ini + 10;

        for($i=$ini;$i<=$fin;$i++)    {

   			$pag = cZeros($i,2);

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $pag ]</b></font>&nbsp;";

            }else{
               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$i&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'> $pag &nbsp; </a>";

            }

        }

		  if($pagina+11 <= $numPags){

           $pg = $pagina + 11;
           echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$pg&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'><img src='lib/imsig.gif' border='0'></a>&nbsp; ";

		  }

		  if($pagina < ($numPags-5)){

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$numPags&filtro=$filtro&filtro3=$filtro3&filtro5=$filtro5&filtro7=$filtro7&filtro9=$filtro9&Vta=$Vta'><img src='lib/imfin.gif' border='0'></a>&nbsp;";

		  }

     }

     echo " &nbsp; </td></tr></table>";

return true;

}

function PonPaginacion4($Bd){
global $inicio,$Msj,$cSql,$pagina,$tamPag,$orden,$numPags,$final,$busca,$nRng,$Sort,$Id,$numeroRegistros,$Vta;
#P k reconoscan el valor k traen

require ('config.php');

    if(sizeof($Bd)>1){
      $Comodin = $Bd[1];
      $Bd      = $Bd[0];
    }

      for ($i = $nRng; $i < $tamPag-2; $i++) {
          echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
      }

      echo "</table>";


    //Mensajes y No de registros;
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'><tr height='25' >";

      //echo "<td width='50%' bgcolor='#b6b6b6'>$Gfont <b> &nbsp; <font color='#ffffff'> $Msj </font></b></td>";
      echo "<td width='60%' align='center'>$Gfont <font color='#900C3F'><b> $Msj </b></td>";

      echo "<td width='40%' align='right'>$Gfont Registros: ".number_format($numeroRegistros,"0")." &nbsp; &nbsp; &nbsp; &nbsp; ";

     // echo "</tr></table>

            //echo $cSql;


      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cSql   = str_replace("'","!",$cSql);                      //Remplazo la comita p'k mande todo el string
      //$cSql   = str_replace("*","|",$cSql);                      //Remplazo la comita p'k mande todo el string
      $Sql    = str_replace("+","~",$cSql);                      //Remplazo la comita p'k mande todo el string

      
     // echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

     // echo "<tr><td height='40'><b>";
     // echo "<td width='20%'><b>";

    // echo "<td align='right' valign='bottom'>$Gfont ";
    //echo "<td align='right' valign='bottom'>$Gfont ";

     if($numPags <= 10 ){

        for($i=1;$i<=$numPags;$i++)    {

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $i ]</b></font>";

            }else{

               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$i&Vta=$Vta'>&nbsp; $i &nbsp;</a>";

            }
        }

     }else{

      $ini = 1;
      if($pagina >= 7){

         $ini = $pagina - 5;

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=1&Vta=$Vta'><img src='lib/imini.gif' border='0'></a>&nbsp;";

        if($pagina-11>=1){

            if($pagina >= $numPags - 3){
               $pg = $ini - 9;
            }else{
                  $pg = $pagina - 11;
               }
               echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$pg&Vta=$Vta'><img src='lib/imant.gif' border='0'></a>&nbsp; ";

        }

         if($ini+10>$numPags){$ini = $numPags - 10;}

      }

      $fin = $ini + 10;

        for($i=$ini;$i<=$fin;$i++)    {

        $pag = cZeros($i,2);

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $pag ]</b></font>&nbsp;";

            }else{
               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$i&Vta=$Vta'> $pag &nbsp; </a>";

            }

        }

      if($pagina+11 <= $numPags){

           $pg = $pagina + 11;
           echo " <a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$pg&Vta=$Vta'><img src='lib/imsig.gif' border='0'></a>&nbsp; ";

      }

      if($pagina < ($numPags-5)){

           echo "<a href='".$_SERVER["PHP_SELF"]."?busca=$busca&pagina=$numPags&Vta=$Vta'><img src='lib/imfin.gif' border='0'></a>&nbsp;";

      }

     }

   echo " &nbsp; </td></tr></table>";

return true;

}

function PonPaginacion5($Bd){
global $inicio,$Msj,$cSql,$pagina,$tamPag,$orden,$numPags,$final,$buscar,$nRng,$Sort,$Id,$numeroRegistros,$Vta,$estudio;
#P k reconoscan el valor k traen

require ('config.php');

    if(sizeof($Bd)>1){
      $Comodin = $Bd[1];
      $Bd      = $Bd[0];
    }

      for ($i = $nRng; $i < $tamPag-2; $i++) {
          echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
      }

      echo "</table>";


    //Mensajes y No de registros;
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'><tr height='25' >";

      //echo "<td width='50%' bgcolor='#b6b6b6'>$Gfont <b> &nbsp; <font color='#ffffff'> $Msj </font></b></td>";
      echo "<td width='60%'>$Gfont <font color='#003399'> $Msj </td>";

      echo "<td width='40%' align='right'>$Gfont Registros: ".number_format($numeroRegistros,"0")."&nbsp;</td>";

      echo "</tr></table>";

            //echo $cSql;


      $Pos    = strrpos($_SERVER[PHP_SELF],".");
      $cLink  = substr($_SERVER[PHP_SELF],0,$Pos).'e.php';     #
      $cSql   = str_replace("'","!",$cSql);                      //Remplazo la comita p'k mande todo el string
      //$cSql   = str_replace("*","|",$cSql);                      //Remplazo la comita p'k mande todo el string
      $Sql    = str_replace("+","~",$cSql);                      //Remplazo la comita p'k mande todo el string

      
      echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

      echo "<tr><td height='40'><b>";

      if($Bd){

         echo " <a class='pg' href='$cLink?busca=NUEVO&Vta=$Vta'>Agrega</a> &nbsp; ";

         echo "<a class='pg' href='bajarep.php?cSql=$Sql'> Exporta </a> &nbsp; ";

         echo "$Comodin  &nbsp; ";

         echo "<a class='pg' href=javascript:winuni('filcampos.php?Id=$Id')>Filtro</a> &nbsp;&nbsp; ";

         echo "<a class='pg' href=javascript:winuni('editcampos.php?Id=$Id')>Campos</a> &nbsp;&nbsp; ";

         echo "<img src='lib/print.png' alt='Imprimir pantalla' border='0' onClick='window.print()'>";

     }

     echo "</b></td><td align='right' valign='bottom'>$Gfont ";
     echo "<td align='right' valign='bottom'>$Gfont ";

     if($numPags <= 10 ){

        for($i=1;$i<=$numPags;$i++)    {

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $i ]</b></font> ";

            }else{

               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?buscar=$buscar&pagina=$i&Vta=$Vta&estudio=$estudio'>&nbsp; $i &nbsp;</a>";

            }
        }

     }else{

      $ini = 1;
      if($pagina >= 7){

         $ini = $pagina - 5;

           echo "<a href='".$_SERVER["PHP_SELF"]."?buscar=$buscar&pagina=1&Vta=$Vta&estudio=$estudio'><img src='lib/imini.gif' border='0'></a>&nbsp;";

        if($pagina-11>=1){

            if($pagina >= $numPags - 3){
               $pg = $ini - 9;
            }else{
                  $pg = $pagina - 11;
               }
               echo " <a href='".$_SERVER["PHP_SELF"]."?buscar=$buscar&pagina=$pg&Vta=$Vta&estudio=$estudio'><img src='lib/imant.gif' border='0'></a>&nbsp; ";

        }

         if($ini+10>$numPags){$ini = $numPags - 10;}

      }

      $fin = $ini + 10;

        for($i=$ini;$i<=$fin;$i++)    {

        $pag = cZeros($i,2);

            if($i==$pagina){

               echo "<font color='#003399'><b>[ $pag ]</b></font>&nbsp;";

            }else{
               echo "<a class='pg' href='".$_SERVER["PHP_SELF"]."?buscar=$buscar&pagina=$i&Vta=$Vta&estudio=$estudio'> $pag &nbsp; </a>";

            }

        }

      if($pagina+11 <= $numPags){

           $pg = $pagina + 11;
           echo " <a href='".$_SERVER["PHP_SELF"]."?buscar=$buscar&pagina=$pg&Vta=$Vta&estudio=$estudio'><img src='lib/imsig.gif' border='0'></a>&nbsp; ";

      }

      if($pagina < ($numPags-5)){

           echo "<a href='".$_SERVER["PHP_SELF"]."?buscar=$buscar&pagina=$numPags&Vta=$Vta&estudio=$estudio'><img src='lib/imfin.gif' border='0'></a>&nbsp;";

      }

     }

     echo " &nbsp; </td></tr></table>";

return true;

}

function CuadroInferior($busca){
global $op,$Dsp,$Vta;

require ('config.php');

echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]?Vta=$Vta'>";

echo "<b>Buscar:</b>&nbsp;<input type='text' name='busca' size='10' maxlength='20'> &nbsp; &nbsp; &nbsp; ";

echo "<input type='hidden' name='pagina' value='1'>";		//Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=&Vta=$Vta'>Limpia pantalla</a>";

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

echo "</form>";

echo "</td></tr></table>";

return true;

}

function CuadroInferior2($busca){
global $op,$Dsp,$Vta;

require ('config.php');

echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]?filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Vta=$Vta'>";

echo "<b>Buscar:</b>&nbsp;<input type='text' name='busca' size='10' maxlength='20'> &nbsp; &nbsp; &nbsp; ";

echo "<input type='hidden' name='pagina' value='1'>";		//Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Vta=$Vta'>Limpia pantalla</a>";

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

echo "</form>";

echo "</td></tr></table>";

return true;

}

function CuadroInferior3($busca){
global $op,$Dsp;

session_start();

require ('config.php');

echo "<meta charset='utf-8'>";

echo "<meta name='viewport' content='width=device-width, initial-scale=1'>";

echo "<link rel='stylesheet' href='//code.jquery.com/ui/1.12.0/themes/base/jquery-ui.css'>";

echo "<link rel='stylesheet' href='/resources/demos/style.css'>";

echo "<script src='https://code.jquery.com/jquery-1.12.4.js'></script>";

echo "<script src='https://code.jquery.com/ui/1.12.0/jquery-ui.js'></script>";

$NomA   = mysql_query("SELECT nombrec FROM cli");

echo "<script>";

echo "$( function() {

    var availableTags = [
	
      while($Nom = mysql_fetch_array($NomA)){

		$Nom(nombrec),
	  };
	
	];

    $( '#tags' ).autocomplete({

      source: availableTags

    });

  } );

	</script>";


echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]'>";

echo "<div class='ui-widget'>";

echo "<label for='tags'>Buscar: </label>";

echo "<input busca='tags'>";

echo "</div>";


//echo "<b>Buscar:</b>&nbsp;<input type='text' name='busca' size='40' maxlength='40'> &nbsp; &nbsp; &nbsp; ";

//echo "<input type='hidden' name='pagina' value='1'>";		//Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca='>Limpia pantalla</a>";

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

echo "</form>";

echo "</td></tr></table>";

return true;

}

function CuadroInferior4($busca){
global $op,$Dsp,$estudio;

require ('config.php');

echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]?estudio=$estudio'>";

echo "<b>Buscar:</b>&nbsp;<input type='text' name='busca' size='10' maxlength='20'> &nbsp; &nbsp; &nbsp; ";

echo "<input type='hidden' name='pagina' value='1'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&busca=&estudio=$estudio'>Limpia pantalla</a>";

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

echo "</form>";

echo "</td></tr></table>";

return true;

}

function CuadroInferior5($buscar){
global $op,$Dsp,$estudio;

require ('config.php');

echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

echo "<form name='form10' method='post' action='$_SERVER[PHP_SELF]?buscar=$buscar&estudio=$estudio'>";

echo "<b>Buscar:</b>&nbsp;<input type='text' name='buscar' size='10' maxlength='20'> &nbsp; &nbsp; &nbsp; ";

echo "<input type='hidden' name='pagina' value='1'>";   //Para saber cuando hay una busqueda nueva y pagina la ponga en 1;

echo "<a  class='pg'  href='$_SERVER[PHP_SELF]?pagina=0&Sort=Asc&buscar=$buscar&estudio=$estudio'>Limpia pantalla</a>";

echo "<font color='#cc0000'> <b>&nbsp; &nbsp; &nbsp;  $Dsp &nbsp; &nbsp; </b></font>";

echo "</form>";

echo "</td></tr></table>";

return true;

}

function CalculaPaginas(){
global $res,$OrdenDef,$limitInf,$pagina,$tamPag,$orden,$numPags,$numeroRegistros;

       if(!isset($_REQUEST[orden])){$orden=$OrdenDef;}else{$orden=$_REQUEST[orden];}

       $numeroRegistros = mysql_num_rows($res);

       $numPags         = ceil($numeroRegistros/$tamPag);	//Redondea hacia arriba 3.14 -> 4;

       if(!isset($pagina) or $pagina <= 0 or $pagina >$numPags){   // Si no trae nada vete hasta e final
          $pagina=$numPags;
       }

       //calculo del limite inferior de registros para tomarlos de la tabla;
       $limitInf=0;
       if($numPags>1){
          if($pagina==$numPags){
          	 $limitInf = $numeroRegistros-$tamPag;
          }else{
             $limitInf = ($pagina-1)*$tamPag;
          }
		 }

return $limitInf;

}


function Botones(){

require ('config.php');

global $pagina,$busca,$Msj,$Vta;

echo "<p align='center'>&nbsp;</p>";

echo "<input type='submit' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='Boton' value='Aceptar'>";

echo "&nbsp; &nbsp; &nbsp; &nbsp;";

echo "<input type='submit' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='Boton' value='Cancelar'>";

echo "&nbsp; &nbsp; &nbsp; &nbsp;";

echo " &nbsp; <img src='lib/print.png' alt='Imprimir' border='0' onClick='window.print()'> &nbsp; ";

echo "<input type='hidden' name='pagina' value=$pagina >";

echo "<input type='hidden' name='busca' value=$busca >";

echo "<input type='hidden' name='Vta' value=$Vta >";

echo "</p>";

echo "<div align='left'>&nbsp; &nbsp; &nbsp; $Gfont <font color='#FF0000'> $Msj </font> </font></div>";
//echo "<div align='left'>&nbsp; &nbsp; &nbsp; $Gfont Mensaje [ <font color='FF0000'> $Msj </font> ]</font></div>";



}
function Botones2(){

require ('config.php');

global $pagina,$busca,$Msj,$Estudio;

echo "<p align='center'>&nbsp;";

echo "<input type='submit' style='background:#aad9aa; color:#ffffff;font-weight:bold;' name='Boton' value='Aceptar'>";

echo "<input type='hidden' name='pagina' value=$pagina >";

echo "<input type='hidden' name='busca' value=$busca >";

//echo "</p>";

//echo "<div align='left'>&nbsp; &nbsp; &nbsp; $Gfont <font color='#FF0000'> $Msj </font> </font></div>";
//echo "<div align='left'>&nbsp; &nbsp; &nbsp; $Gfont Mensaje [ <font color='FF0000'> $Msj </font> ]</font></div>";


}


function CierraWin(){    //Cierra la ventana principal
  echo "</td></tr></table>";
}

function cTable($Tam,$Borde){    //Abre tabla
   echo "<table width='$Tam'  border='0' cellpadding='0' cellspacing='2'>";
}

function cTableCie(){						//Cierra tabla
	echo "</table>";
}
function cInput($Titulo,$Tipo,$Lon,$Campo,$Alin,$Valor,$MaxLon,$Mayuscula,$Ed,$Nota){
require ('config.php');

//cInput("Codigo del acabado:","text","20",'Codigo','right',$Cpo[codigo],'20',true,true);
// Titulo, tipo, longitud del campo, Variable en la k regresa, alineacion, Valor por default,maximo de letras,Si lo convierte en mayusculas,edita el campo
  //echo "<tr height='27'><td align=$Alin>$Gfont <b>$Titulo</b> &nbsp; </font></td>";
  echo "<tr><td align=$Alin  bgcolor='#e1e1e1'>$Gfont $Titulo &nbsp; </font></td>";
  if(strlen($Tipo)>1){
      if($Ed){				// No se puede modificar el campo solo se edita
   	    echo "<td>$Gfont $Valor &nbsp; $Nota</td></tr>";
      }else{
          if($Mayuscula){
             echo "<td><input type=$Tipo style='background-color:$InputCol;color:#200010;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>$Gfont $Nota</td></tr>";
             //echo "<td>$Gfont <input type=$Tipo style='background-color:$InputCol;color:#ffffff;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH=$MaxLon onBLur=Mayusculas('$Campo')>$Gfont $Nota</td></tr>";
          }else{
              //echo "<td>$Gfont <input type=$Tipo style='background-color:$InputCol;color:#ffffff;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon'>$Gfont $Nota</td></tr>";
              echo "<td><input type=$Tipo style='background-color:$InputCol;color:#200010;' name=$Campo size='$Lon' value='$Valor' MAXLENGTH='$MaxLon'>$Gfont $Nota</td></tr>";

          }
     }
   }
}

function Calidad($Peso){
global $Cal,$nPri,$nSeg,$nTer,$n1,$n2,$n3;

  //Criterio anterior;
  //if($Pro[3] <= 0){$nPri++;}elseif($Pro[3] <= 4){$nSeg++;}else{$nTer++;}

  if($Cal <= 0){$nPri++;$n1+=$Peso;}elseif($Cal <= 4){$nSeg++;$n2+=$Peso;}else{$nTer++;$n3+=$Peso;}


}

function Display($aCps, $aDat, $registro) {
    require ('config.php');
    for ($i = 0; $i < sizeof($aCps); $i++) {
        if ($aDat[$i * 3 + 2] == 'N') {
            echo "<td align='right'>$Gfont <font size='1'> &nbsp; " . number_format($registro[$i], '2') . " &nbsp;</font></a></td>";
        } elseif($aDat[$i * 3 + 2] == 'F') {
            $Align = 'left';
            echo "<td align='right'>$Gfont <font size='1'> &nbsp; " . number_format($registro[$i], '4') . " &nbsp;</font></a></td>";
        } else {
            $Align = 'left';
            echo "<td>$Gfont <font size='2'> &nbsp;" . ucwords(strtolower($registro[$i])) . "&nbsp;</font></a></td>";
        }
    }
}

//Obten el precio de la tela mandando el estilo y el color;
function PreEstClr($Estilo,$Color){

  $xPrecA = mysql_query("SELECT est.gam1,est.gam2,est.gam3,est.gam4,est.gam5,colt.gama
	                      FROM est,colt
	                      WHERE est.estilo='$Estilo' AND colt.color='$Color'");

	$xCpo   = mysql_fetch_array($xPrecA);

   $PreVta = 'gam'.$xCpo[gama];

   $Precio = $xCpo[$PreVta];

	return $Precio;

}


function Stats($cVlr){
require ('config.php');

echo "<tr><td align='right'>$Gfont <b>Status: </b>&nbsp; </td><td>";

if($cVlr<>'CERRADA'){
   if(isset($cVlr)){
      echo "<select name='Status'>";
      echo "<option value='ABIERTA'>ABIERTA</option>";
      echo "<option value='CERRADA'>CERRADA</option>";
      echo "<option selected value='$cVlr'>$cVlr</option>";
   }else{
     echo "<select name='Status' disabled>";
     echo "<option selected value='ABIERTA'>ABIERTA</option>";
   }
}else{
   echo "<select name='Status' disabled>";
   echo "<option value='ABIERTA'>ABIERTA</option>";
   echo "<option value='CERRADA'>CERRADA</option>";
   echo "<option selected value='$cVlr'>$cVlr</option>";
}
echo "</selected>";
echo "</td></tr>";
}

function Elimina(){
global $busca;

if($busca<>'NUEVO'){
   echo "<br>";
   echo "<div align='center'>Para eliminar &eacute;ste movimiento, favor de poner el password y dar click en el boton de <b>Eliminar</b></div>";
   echo "<div align='center'>Password: ";
   echo "<input type='password' style='background-color:#bacbc2;color:#ffffff;font-weight:bold;' name='Passw' size='15' maxlength='15'>";
   echo " &nbsp; <input type='submit' name='Boton' value='Eliminar'></div>";
}
}

function Btc($Txt,$Elm){

session_start();

$Usr   = $_COOKIE['USERNAME'];
$Fecha = date("Y-m-d");
$Hora  = date("H:i");

$lUp   = mysql_query("INSERT INTO bit (fecha,hora,usr,accion,elemento)
         VALUES
         ('$Fecha','$Hora','$Usr','$Txt','$Elm')");

}



function IncrementaFolio($Campo,$Suc){

	  $FolA    = mysql_query("SELECT $Campo FROM cia WHERE id='$Suc'");
	  $lUp     = mysql_query("UPDATE cia SET $Campo = $Campo + 1 WHERE id='$Suc'");
	  $Fol     = mysql_fetch_array($FolA);
	  $FolioU  = $Fol[$Campo];

	 return $FolioU;

}

function RestarHoras($horaini,$horafin)
{
	$horai=substr($horaini,0,2);
	$mini=substr($horaini,3,2);
	$segi=substr($horaini,6,2);
 
	$horaf=substr($horafin,0,2);
	$minf=substr($horafin,3,2);
	$segf=substr($horafin,6,2);
 
	$ini=((($horai*60)*60)+($mini*60)+$segi);
	$fin=((($horaf*60)*60)+($minf*60)+$segf);
 
	$dif=$fin-$ini;
 
	$difh=floor($dif/3600);
	$difm=floor(($dif-($difh*3600))/60);
	$difs=$dif-($difm*60)-($difh*3600);
	return date("H:i:s",mktime($difh,$difm,$difs));
}

?>
