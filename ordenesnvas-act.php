<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  //require("lib/kaplib.php");
  require("lib/kaplib.php");
  date_default_timezone_set("America/Mexico_City");
  $Linea  = array('','UNO','DOS','TRES','CUATRO','CINCO');

  //$Usr    = $check['uname'];

  $Fec    = date('Y-m-d');
  $Usr    = $_COOKIE['USERNAME'];
  $Suc    = $_COOKIE['TEAM'];        //Sucursal 

  //$Titulo = "Captura de ordenes de estudio";
  //$Titulo = "Recepci&oacute;n(caja)";

  $link   = conectarse();

  $tamPag = 15;

  $pagina = $_REQUEST[pagina];

  //$Vta=$_REQUEST[Vta];

  if(!isset($_REQUEST[Vta])){$Vta=$_SESSION['Venta_ot'];}else{

     $Vta = $_REQUEST[Vta];

     $_SESSION['Venta_ot']=$_REQUEST[Vta];


  } #En caso k venga del cat.de clientes(cliventas) y desde ahi manda la clave

  $op      = $_REQUEST[op];

  $busca   = $_REQUEST[busca];

  $OrdenDef= "estudio";      //Orden de la tabla por default

  $Estudio = strtoupper($_REQUEST[Estudio]);

  if($op=="pr"){			//Cuando entras p agregar el encabezado

     $Fecha  = date("Y-m-d");
     $InsA   = mysql_query("SELECT lista,descuento,condiciones,msjadministrativo FROM inst WHERE institucion='$_REQUEST[Institucion]'",$link);    #Checo la lista de la institucion
     $Ins    = mysql_fetch_array($InsA);
     $Upd    = mysql_query("UPDATE otnvas set inst='$_REQUEST[Institucion]',lista='$Ins[0]' WHERE usr='$Usr' and venta='$Vta'",$link);
     $UpdA   = mysql_query("SELECT * FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
     $Upd    = mysql_fetch_array($UpdA);
     if(!$Upd[usr]=="$Usr"){
        $lUp = mysql_query("INSERT INTO otnvas (inst,lista,usr,venta,servicio,fechae) VALUES ('1','$Ins[0]','$Usr','$Vta','Ordinaria','$Fecha')",$link);
     }
	 
	 if($Ins[msjadministrativo]<>''){
		$mss=$Ins[msjadministrativo];
		echo '<script language="javascript">alert("'.$mss.'");</script>'; 
	 }

  }elseif($op=='Estudio'){        // Agrega Estudios a la Venta


    $OtnvaA   = mysql_query("SELECT lista,fechae,servicio,inst FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    if($Otnva = mysql_fetch_array($OtnvaA)){

        $Fecha = date("Y-m-d");
        $Fecha = strtotime($Fecha);
        $Lista = "lt".ltrim($Otnva[lista]);

        if($Otnva[lista] <> 0){
		
		   $InsB   = mysql_query("SELECT lista,descuento FROM inst WHERE institucion='$Otnva[inst]'",$link);    #Checo la lista de la institucion
		   $Ins2    = mysql_fetch_array($InsB);


           $EstA   = mysql_query("SELECT estudio,descripcion,$Lista,entord,entexp,enthos,enturg,activo
                     FROM est
                     WHERE estudio='$Estudio' and activo<>'No'");

           $cCpo    = mysql_fetch_array($EstA);
           
           if($cCpo[estudio] <> ''){
			   
               if( $cCpo[$Lista] > 0){
               
                    //$Estudio = strtoupper($_REQUEST[Estudio]);
                    $lUp     = mysql_query("INSERT INTO otdnvas (usr,estudio,descripcion,descuento,precio,venta)
                               VALUES
                               ('$Usr','$Estudio','$cCpo[1]','$Ins2[descuento]','$cCpo[$Lista]','$Vta')");

                    if($Otnva[servicio]=="Ordinaria"){$Dias=$cCpo[entord];}else{$Dias=$cCpo[entexp]/24;}

                    $nDias   = strtotime("$Dias days",$Fecha);     //puede ser days month years y hasta -1 month menos un mes...
                    $Fechae  = date("Y-m-d",$nDias);
                    
                    if($Otnva[fechae] < $Fechae ){   //Checa y autaliza de fecha de entrada
                        $Otnva = mysql_query("UPDATE otnvas set fechae = '$Fechae' WHERE usr='$Usr' and venta='$Vta'",$link);
                    }

                }else {

                   $Msj = "Precio en ceros, favor de verificar";
                   
               }
               
           }else{
              $Msj = "El Estudio [$Estudio] no existe o se encuentra Inactivo, favor de verificar";
           }
        }else{
              $Msj = "Aun no has elegido la institucion, favor de verificar";
        }
     }else{
        $Msj = "Aun no as elegido la Institucion(lista/precios), favor de verificar";
     }
  }elseif($op=="br"){  // borra todo

    $borrtodo=$_REQUEST[bt];
   
    if($borrtodo<>'si'){
      echo "<script>window.open('impotpdf.php?busca=$_REQUEST[id]','_blank','height=800,width=1000');</script>";
    }
      


     $Fecha  = date("Y-m-d");
     $Horae  = "15:00";
     $lUp    = mysql_query("delete FROM otdnvas WHERE usr='$Usr' and venta=$Vta",$link);
     $lUp    = mysql_query("delete FROM otnvas WHERE usr='$Usr' and venta=$Vta",$link);
     $InsA   = mysql_query("SELECT lista FROM inst WHERE institucion='1'",$link);    #Checo la lista de la institucion
     $Ins    = mysql_fetch_array($InsA);
     $lUp    = mysql_query("INSERT INTO otnvas (inst,lista,usr,venta,servicio,fechae,horae)
               VALUES
               ('0','0','$Usr','$Vta','Ordinaria','$Fecha','$Horae')");

  }elseif($op=="cl"){ //CLiente

     $CliA    = mysql_query("SELECT cliente FROM cli WHERE cliente='$_REQUEST[Cliente]'",$link);
     if($Cli=mysql_fetch_array($CliA)){
         $lUp = mysql_query("UPDATE otnvas set cliente = '$_REQUEST[Cliente]' WHERE usr='$Usr' and venta='$Vta'",$link);
     }else{
         $Msj = "El No. de paciente ".$Cliente." no existe";
     }

  }elseif($op=="md"){ //Med
     $Medico = strtoupper($_REQUEST[Medico]);
     $MedA   = mysql_query("SELECT medico,status FROM med WHERE medico='$Medico' and status<>'Baja' and status<>'Defuncion'",$link);
     if($Med=mysql_fetch_array($MedA)){
        $lUp=mysql_query("UPDATE otnvas set medico = '$Medico' WHERE usr='$Usr' and venta='$Vta'",$link);
     }else{
        $Msj="La Clave del Medico ".$Medico ." no existe, Baja o Defuncion...";
     }
  }elseif($op=="Eli"){ //Elimina     $Medico=strtoupper($Medico);
    $lUp=mysql_query("delete FROM otdnvas WHERE usr='$Usr' and venta='$Vta' and estudio='$busca' limit 1",$link);
  }elseif($op=="Rc"){
    $lUp=mysql_query("UPDATE otnvas set receta = '$_REQUEST[Receta]',fechar='$_REQUEST[Fechar]',abono=$_REQUEST[Abono] WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="dtg"){ //Aplica el descuento gral.
    $lUp=mysql_query("UPDATE otdnvas set descuento = '$_REQUEST[Descuento]' WHERE usr='$Usr' and venta='$Vta'",$link);
    $lUp=mysql_query("UPDATE otnvas set descuento = '$_REQUEST[Razon]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="dt"){ //Aplica el descuento detalle por estudio
    $lUp=mysql_query("UPDATE otdnvas set descuento = '$_REQUEST[Descuento]' WHERE usr='$Usr' and venta='$Vta' and estudio='$Estudio'",$link);
    $lUp=mysql_query("UPDATE otnvas set descuento = '$_REQUEST[Razon]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Sv"){
    $lUp=mysql_query("UPDATE otnvas  set servicio='$_REQUEST[Servicio]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Fc"){   // Cambai fecha de etrega
    $lUp=mysql_query("UPDATE otnvas set fechae = '$_REQUEST[Fechae]',horae = '$_REQUEST[Horae]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Fn"){               // Genera la Orden de trabajo
      
      
    $Folio   = cZeros(IncrementaFolio('otfolio',$Suc),5);
    
    $Folioc  = cZeros(IncrementaFolio('cajafolio',$Suc),5);
                    

    $Fecha = date("Y-m-d");
    $Hora1 = date("H:i");
    //$Hora2 = strtotime("-60 min",strtotime($Hora1));
    //$hora  = date("H:i",$Hora2);

    $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30
    $OtdA = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otdnvas WHERE usr='$Usr' and venta='$Vta'");
    $Otd = mysql_fetch_array($OtdA);
    $OtA = mysql_query("SELECT * FROM otnvas WHERE usr='$Usr' AND venta='$Vta'", $link);
    $Ot = mysql_fetch_array($OtA);

    $Abono = $_REQUEST[Abono];

    $cPag = 'No';

    if ($Abono + .5 >= $Otd[0]) {
        $cPag = 'Si';
    }
	
	if ($Ot[medico]<>''){
		$medico=$Ot[medico];
	}else{
		$medico="AQ";
	}
	
    $lUp = mysql_query("INSERT INTO ot
          (cliente,fecha,hora,medico,fecharec,fechae,institucion,diagmedico,observaciones,servicio,recepcionista,
          receta,importe,descuento,pagada,fecpago,medicon,status,horae,suc,folio,datoc)
          VALUES
          ('$Ot[cliente]','$Fecha','$hora','$medico','$_REQUEST[Fechar]','$Ot[fechae]','$Ot[inst]',
          '$_REQUEST[Diagmedico]','$_REQUEST[Observaciones]','$Ot[servicio]','$Usr','$_REQUEST[Receta]',$Otd[0],
          '$Ot[descuento]','$cPag','$Fecha','$_REQUEST[Medicon]','DEPTO','$Ot[horae]','$Suc','$Folio','$_REQUEST[Datoc]')");
    
    $Id = mysql_insert_id();

    $lUp = mysql_query("UPDATE cli SET numveces=numveces+1 WHERE cliente='$Ot[cliente]' LIMIT 1");

    $lUpA = mysql_query("SELECT otdnvas.estudio,otdnvas.precio,otdnvas.descuento,est.depto
            FROM otdnvas,est
            WHERE usr='$Usr' and venta='$Vta' and otdnvas.estudio=est.estudio");    #Checo k bno halla estudios capturados

    $lBd = false;

    while ($lUp = mysql_fetch_array($lUpA)) {
        $Depto = 'DEPTO'; //kda en el depto
        $PreA = mysql_query("SELECT estudio,pre FROM pred WHERE estudio='$lUp[0]'");  //Si existe en preanaliticos
        while ($lPre = mysql_fetch_array($PreA)) {  //Si tiene pregts.de pre-analitico los da de alta en otpre
            $lBd = true;
            //$Depto='PRE-A';   Se van directos al Depto ya no pasan por pre-analiticos
            //$ExiA=mysql_query("SELECT pregunta FROM otpre WHERE orden='$Id' and pregunta='$lPre[1]'",$link);  //Si existe en preanaliticos
            //if(!$lEx=mysql_fetch_array($ExiA)){ //Si no ex.la preg la agrga
            $lEx = mysql_query("INSERT INTO otpre (orden,estudio,pregunta) VALUES ($Id,'$lUp[0]','$lPre[1]')");
            //}
        }

        $lOtd = mysql_query("INSERT INTO otd (orden,estudio,precio,descuento,status)
                 VALUES
                 ($Id,'$lUp[estudio]','$lUp[precio]','$lUp[descuento]','$Depto')");

        if ($lUp[depto] == 2) {                  // Si es que es de radiologia se crea un archivo en base a un formato del word y lo copio
            $FilWord = strtolower("informes/" . $lUp[estudio] . ".doc");
            $FilOut = strtolower("textos/" . $lUp[estudio] . $Id . ".doc");

            if (file_exists($FilWord)) {
                copy($FilWord, $FilOut);
            }
        }
    }

    if ($Abono > 0) {
        $nAb = $Abono;
    } else {
        $nAb = .5;
    }

    $Tpago = $_REQUEST[Tpago];

    $lUp = mysql_query("INSERT INTO cja (orden,fecha,hora,usuario,importe,tpago,suc,folio)
    		    VALUES
    		    ($Id,'$Fecha','$hora','$Usr','$nAb','$Tpago','$Suc','$Folioc')");

    //mysql_close($link);

   // ******* header("Location: impot.php?busca=$Id&Vta=$Vta");

    
   header("Location: ordenesnvas.php?op=br&id=$Id");
    
}

  $MsA    = mysql_query("SELECT count(*) FROM msj WHERE para='$Usr' AND !bd");
  $Ms     = mysql_fetch_array($MsA);
  $nMsj   = $Ms[0];

  $HeA    = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otdnvas WHERE usr='$Usr' and venta='$Vta'");
  $He     = mysql_fetch_array($HeA);

  $cSql   = "SELECT estudio,descripcion,precio,descuento
            FROM otdnvas
            WHERE usr='$Usr' and venta='$Vta'";

  $Edit   = array("","Estudio","Descripcion","Precio","%Dto","Importe","Elim","-","-","-","-","-","-","-");

  //$aPrg   = array('Ninguno','Cliente frecuente','Diabetes','Chequeo medico','Otro');
  
  $aPrg = array('Ninguno','Cliente frecuente','Apoyo a la salud','Chequeo medico','Empleado','Familiar','Medico','Especializado');

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<?php

   echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";

   //headymenu($Titulo,0);

   echo "<table width='100%' border='0'>";

   echo "<tr>";

   echo "<td width='15%' align='left'><a href='menu.php'><img  src='lib/logo2.jpg' border='0'></a></td>";

   echo "<td>&nbsp;</td>";

	echo "<td width=91><img  src='lib/logo40.jpg' border='0'></td>";

   echo "</tr></table>";

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

//function Completo(){
//var lRt;
//lRt=true;

//if(document.form3.Cliente.value=="0"){lRt=false;}
//if(document.form3.Cliente.value==" "){lRt=false;}
//if(document.form3.Cliente.value==""){lRt=false;}
//if(document.form4.Medico.value==""){lRt=false;}
//if(document.form4.Medico.value==" "){lRt=false;}
//if(document.form4.Medico.value=="  "){lRt=false;}
//if(!lRt){
//   alert("Son necesarios los datos de cliente y/o medico, para generar la orden de estudio");
//   return false;
//}else{
//  return true;
//}
//}
function Ventana(url){
   window.open(url,"venord","width=750,height=500,left=40,top=50,scrollbars=yes,location=no,dependent=yes,resizable=yes")
}
</script>

<?php
/*
echo "<table width='100%' border='0' cellpadding=0 cellspacing=0 bgcolor='#FFFFFF'>";
echo "<tr>";
//echo "<td background='lib/imgbackg.gif' height='70' valign='top'>$Gfont <br> ";
echo "<td height='70' valign='top'>$Gfont ";
echo "<font size='+2' color='#C10100'><b>&nbsp;$Gcia</font>";
echo "<div> &nbsp; &nbsp; $Nom</div>";
echo "</td>";
echo "<td align='right'><img src='lib/img5.gif'></td>";
echo "</tr>";
echo "</table>";
*/

$OtA  = mysql_query("SELECT inst,lista,cliente,medico,receta,fechar,observaciones,diagmedico,servicio,fechae,abono,horae
        FROM otnvas
        WHERE venta='$Vta' and usr='$Usr'");
$Ot   = mysql_fetch_array($OtA);

$InsA = mysql_query("SELECT institucion,nombre,alias,descuento,condiciones FROM inst where status='ACTIVO' order by institucion");

$MedA = mysql_query("SELECT nombrec,clasificacion,mail FROM med WHERE medico='$Ot[medico]'");
$Med  = mysql_fetch_array($MedA);

$CliA = mysql_query("SELECT nombrec,programa,observaciones,colonia,municipio,numveces,mail FROM cli WHERE cliente='$Ot[cliente]'");
$Cli  = mysql_fetch_array($CliA);

echo "<table width='100%' cellpadding=0 cellspacing=0 border='0'>";

echo "<tr height='36'><td background='menu/left_cap.gif' width='5'>&nbsp;</td>";

echo "<td background='menu/center_tile.gif'>$Gfont ";

echo "&nbsp; <a class='vt' href='ordenesnvas.php?Vta=1'> 1</a> &nbsp; &nbsp; ";

echo "<a class='vt' href='ordenesnvas.php?Vta=2'> 2</a> &nbsp; &nbsp; ";

echo "<a class='vt' href='ordenesnvas.php?Vta=3'> 3</a> &nbsp; &nbsp; ";

echo "<a class='vt' href='ordenesnvas.php?Vta=4'> 4</a> &nbsp; &nbsp; ";

echo "<a class='vt' href='ordenesnvas.php?Vta=5'> 5</a> &nbsp; &nbsp; ";

echo "[ $Linea[$Vta] ] &nbsp; &nbsp; ";

echo "<a class='vt' href='ordenesnvas.php?Vta=$Vta&Usr=$Usr&op=br&bt=si'>[Borra/todo]</a> &nbsp; ";

echo "<a class='vt' href='descuentos.php?Vta=$Vta&op=dtg'>[Descto/Gral]</a>";

echo "</td>";

echo "<td background='menu/center_tile.gif' align='right'>$Gfont ";

echo "<img src='lib/msjn.png' border='0'><font color='#ffffff'> $Usr ";
echo " | Sin leer <a class='vt' href=javascript:winuni('msjrec.php')> $nMsj <font color='#69b747'>  mensaje(s) </font></a>";
echo " | <a class='vt' href=javascript:winuni('msjenve.php?busca=NUEVO')> Nvo.mensaje</a> | ";
echo " <a class='vt' href='logout.php'> Salir</a><img src='lib/exit.png' border='0'> &nbsp; ";
echo "</td>";
echo "<td background='menu/right_cap.gif' width='5'>&nbsp;</td>";
echo "</tr></table>";



  echo "<table align='center' width='100%' border='1' cellpadding='0' cellspacing='0' background='lib/fondo.gif' >";
  //echo "<table align='center' width='100%' border='0'>";
  echo "<tr>";
    echo "<td width='26%' valign='top'>";

        echo "<form name='form1' method='post' action='ordenesnvas.php?op=pr'>";
        echo "$Gfont Institucion: ";
        echo "<select class='textos' name='Institucion'>";
        while ($Ins=mysql_fetch_array($InsA)){
            echo "<option value=$Ins[0]> $Ins[0] ".ucwords(strtolower($Ins[alias]))."</option>";
            if($Ins[0]==$Ot[inst]){$DesIns=ucwords(strtolower($Ins[alias]));}
            if($Ins[0]==1){$DesInsD=ucwords(strtolower($Ins[alias]));}
        }
        if($Ot[inst]==''){
            echo "<option selected value='1'>1&nbsp;$DesInsD</option>";
        }else{
            echo "<option selected value='$Ot[inst]'>$Ot[inst]&nbsp; ".ucwords(strtolower($DesIns))."</option>";
        }
        echo "</select>";
        echo "&nbsp;<input class='textos' type='submit' name='Submit' value='Enviar'>";
        echo "<input type='hidden' name='Vta' value=$Vta> ";
        echo "<a href=javascript:winuni('instobs.php?busca=$Ot[inst]')><img src='lib/int4.gif' border='0' height='25'></a><br>";
        echo " &nbsp; Lista/prec: $Ot[lista]";
		$InsC = mysql_query("SELECT condiciones,mail,msjadministrativo FROM inst where institucion=$Ot[inst]");
		$Ins2=mysql_fetch_array($InsC);
		if ($Ins2[condiciones]=='CONTADO'){
	        echo " <font color='#000099'> &nbsp; ".ucwords(strtolower($Ins2[condiciones]));
		}else{
			echo " <font color='#CC0000'> &nbsp; ".ucwords(strtolower($Ins2[condiciones]));
		}
        echo "</font><br> &nbsp; Mail: $Ins2[mail]";
        echo "</form>";

    echo "</td>";
    echo "<td width='45%' valign='top'>";

      echo "<form name='form9' method='post' action='ordenesnvas.php?op=Fc&Vta=$Vta'><font size='-1'>";
      echo "$Gfont Fec/ent: &nbsp; ";
      echo "<input class='textos' type=text name='Fechae' value ='$Ot[fechae]' maxlength='10' size='10'>";
      echo " &nbsp;Hra: ";
      echo "<input class='textos' type='text' name='Horae' value ='$Ot[horae]' maxlength='8' size='5'>&nbsp;";
      echo " &nbsp; <input class='textos' type='submit' name='Submit' value='Enviar'>";
      echo "</form>";

    echo "</td>";
    echo "<td valign='top'>";

        echo "<form name='form8' method='post' action='ordenesnvas.php?op=Sv'>";
        if($Ot[servicio]==""){$DisSer="Ordinaria";}else{$DisSer=$Ot[servicio];}
        echo "$Gfont Servicio: ";
        echo "<select class='textos' name='Servicio'>";
        echo "<option value='Ordinaria'>Ordinaria</option>";
        echo "<option value='Urgente'>Urgente</option>";
        echo "<option value='Express'>Express</option>";
        echo "<option value='Hospitalizado'>Hospitalizado</option>";
        echo "<option value='Nocturno'>Nocturno</option>";
        echo "<option selected value='$DisSer'>$DisSer</option>";
        echo "</select>";
        echo "&nbsp;&nbsp;<input class='textos' type='submit' name='Submit' value='Enviar'>";

        echo "</form>";
    echo "</td>";
  echo "</tr>";

  echo "<tr>";
    echo "<td valign='top'>";
     echo "<form name='form2' method='post' action='ordenesnvas.php?op=Estudio&Vta=$Vta'> ";
         echo "$Gfont Estudio: ";
         echo "<input class='textos' class='texto' name='Estudio' type='text' size='5'> &nbsp; ";
         echo "<a href='agrestord.php?Vta=$Vta&Usr=$Usr&orden=est.descripcion'><img src='lib/lupa_o.gif' alt='Busca en el catalogo de estudios' border='0'></a>";
     echo "</form>";
     //echo " <a class='pg' href='ordenes.php?orden=ot.orden'><img src='lib/regresa.jpg' border=0></a> ";
     echo " &nbsp; <a class='pg' href='ordenes.php?orden=ot.orden'>Regresar</a> ";

   echo "</td>";
    echo "<td valig='top'>";
        echo "<form name='form3' method='post' action='ordenesnvas.php?op=cl&Vta=$Vta'>";
            $nPrg=$Cli[programa];
            echo "$Gfont Paciente: ";
            echo "<input class='textos' name='Cliente' type='text' value='$Ot[cliente]' size='3'>";
            echo " &nbsp; <a href='clientesord.php?Vta=$Vta&busca=ini'><img src='lib/lupa_o.gif' border='0'></a>";
            echo " &nbsp; Programa: $aPrg[$nPrg] &nbsp; &nbsp; ";
            
            if($Cli[observaciones]<>''){
               echo "<a href=javascript:winuni('cliobs.php?busca=$Ot[cliente]')><img src='lib/int4.gif' border='0' height='25'></a>";
            }
            echo "&nbsp; &nbsp; vecs: <font color=#FF0000><b>". $Cli[numveces]."</b>";
            echo "<br><a class='vt' href=javascript:winuni('Upcliente.php?busca=$Ot[cliente]') title='Click para actualizar sus datos'>&nbsp; ". ucwords(strtolower(substr($Cli[nombrec],0,35)))."</a>";
            echo "</font> / Col.".ucwords(strtolower($Cli[colonia])) . " " . ucwords(strtolower($Cli[municipio]));
            echo "</font>$Gfont<br> &nbsp; Mail: $Cli[mail] </font>";
        echo "</form>";

    echo "</td>";
    echo "<td valign='top'>";
      echo "<form name='form4' method='post' action='ordenesnvas.php?Vta=$Vta&op=md'>";
         echo "$Gfont Medico: </strong>";
         echo "<input class='textos' class='textos' name='Medico' type='text' value='$Ot[medico]' size='8'> ";
         echo "<a href='medicos.php?orden=med.medico&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*'><img src='lib/lupa_o.gif' alt='Catalogo de medicos' border='0'></a>";
            echo " &nbsp; Clasif.: <font color=#FF0000><b> $Med[clasificacion]</b> &nbsp; </font>";
            if($Med[clasificacion]<>''){
               echo "<a href=javascript:winuni('medobs.php')><img src='lib/int4.gif' border='0' height='25'></a>";
            }
            echo "&nbsp; &nbsp; <a href=javascript:winuni('medicobs.php?orden=$Ot[medico]')><img src='lib/analiza.gif' border='0' height='25'></a><br> &nbsp;&nbsp;";
        echo "<font color='#000099'>". ucwords(strtolower(substr($Med[nombrec],0,45)));
        echo "</font><br> &nbsp; Mail: $Med[mail]";
      echo "</form>";
    echo "</td>";
  echo "</tr>";

echo "</table>";

  if(!$res=mysql_query($cSql.$cWhe,$link)){

        cMensaje("No se encontraron resultados � hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

		  if($limitInf < 0){$limitInf=0;}

        $sql = $cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

        $res = mysql_query($sql,$link);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){

            if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

            echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
            $estud = strtolower($registro[estudio]);            
            echo "<td align='center'><a href=javascript:Ventana('informes/$estud.php')><img src='images/print.png' alt='Elimina' border='0'></a></td>";
            echo "<td align='left'><a class='vt' href=javascript:winuni('estudiosobs.php?estudio=$registro[estudio]')>$registro[estudio]</a></td>";
            echo "<td>$Gfont $registro[descripcion]</td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio],"2")."</td>";
            echo "<td align='center'>$Gfont <a class='pg' href='descuentos.php?Vta=$Vta&op=dt&Estudio=$registro[estudio]'>$registro[descuento]</a></td>";
            echo "<td align='right'>$Gfont ".number_format($registro[precio]*(1-$registro[descuento]/100),"2")."</td>";
            echo "<td align='center'>$Gfont <a href='ordenesnvas.php?op=Eli&busca=$registro[estudio]&Vta=$Vta'><img src='lib/dele.png' alt='Elimina' border='0'></a></td>";
            echo "</tr>";
            $nImp=$nImp+($registro[precio]*(1-($registro[descuento]/100)));
            $nRng++;
        }

        //echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>$Gfont <b> $ Importe &nbsp; </b></td><td align='right'>$Gfont <b> ".number_format($nImp,'2')." </b></font></td><td>&nbsp;</td></tr>";
        echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>$Gfont </td><td align='right'>$Gfont <b>$ &nbsp; ".number_format($He[0],'2')." </b></font></td><td>&nbsp;</td></tr>";

        PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    //echo "<hr>";

    echo "<table width='100%' height='80'  border='0' cellpadding='0' cellspacing='0'>";

    echo "<tr background='lib/prueba.jpg'><td>$Gfont ";

    echo "<form name='formNva' method='post' action='ordenesnvasd.php?Vta=$Vta&op=md&Abono=$nImp' onSubmit='return Completo();'>";

        echo "<input type='submit' name='Genera' value='Genera Orden de Estudios'> &nbsp; &nbsp; ";
        echo "<a class='vt' href=javascript:Ventana('cotizacion.php?Vta=$Vta')>Cotizacion</a> </font> &nbsp; &nbsp; ";
        echo "<a class='vt' href=javascript:Ventana('impos.php')>Servicio a domicilio</a> </font>";

    echo "</form>";

   echo "</td></tr></table>";

    mysql_close();

echo "</body>";

echo "</html>";

?>
<style type='text/css'>

a.vt:link {

 color: #003c72;

    font-size: 14px;

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

