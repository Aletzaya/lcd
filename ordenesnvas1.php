<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Linea=array('','UNO','DOS','TRES','CUATRO','CINCO');

  $Usr=$check['uname'];

  $Titulo="Captura de ordenes de estudio";

  $link=conectarse();

  $tamPag=10;

  //$Vta=$_REQUEST[Vta];

  if(!isset($_REQUEST[Vta])){$Vta=$_SESSION['Venta_ot'];}else{

     $Vta=$_REQUEST[Vta];

     $_SESSION['Venta_ot']=$_REQUEST[Vta];


  } #En caso k venga del cat.de clientes(cliventas) y desde ahi manda la clave

  $op=$_REQUEST[op];

  $busca=$_REQUEST[busca];

  $OrdenDef="estudio";      //Orden de la tabla por default

  $Estudio=$_REQUEST[Estudio];

  if($op=="pr"){			//Cuando entras p agregar el encabezado

     $Fecha=date("Y-m-d");
     $InsA=mysql_query("SELECT lista from inst WHERE institucion='$_REQUEST[Institucion]'",$link);    #Checo la lista de la institucion
     $Ins=mysql_fetch_array($InsA);
     $Upd=mysql_query("update otnvas set inst='$_REQUEST[Institucion]',lista='$Ins[0]' WHERE usr='$Usr' and venta='$Vta'",$link);
     $UpdA=mysql_query("SELECT * from otnvas WHERE usr='$Usr' and venta='$Vta'");
     $Upd=mysql_fetch_array($UpdA);
     if(!$Upd[usr]=="$Usr"){
        $lUp=mysql_query("insert into otnvas (inst,lista,usr,venta,servicio,fechae) VALUES ('1','$Ins[0]','$Usr','$Vta','Ordinaria','$Fecha')",$link);
     }

  }elseif($op=='Estudio'){        // Agrega Estudios a la Venta
    $OtnvaA=mysql_query("SELECT lista,fechae,servicio from otnvas WHERE usr='$Usr' and venta='$Vta'",$link);
    if($Otnva=mysql_fetch_array($OtnvaA)){
        $Fecha=date("Y-m-d");
        $Fecha=strtotime($Fecha);
        $Lista="lt".ltrim($Otnva[lista]);
        $lUp=mysql_query("SELECT estudio,descripcion,$Lista,entord,entexp,enthos,enturg from est WHERE estudio='$_REQUEST[Estudio]' ",$link);
        if($cCpo=mysql_fetch_array($lUp)){
           $Estudio=strtoupper($_REQUEST[Estudio]);
           $lUp=mysql_query("insert into otdnvas (usr,estudio,descripcion,descuento,precio,venta) VALUES ('$Usr','$Estudio','$cCpo[1]',0,'$cCpo[$Lista]','$Vta')",$link);
           if($Otnva[servicio]=="Ordinaria"){$Dias=$cCpo[entord];}else{$Dias=$cCpo[entexp]/24;}
           $nDias=strtotime("$Dias days",$Fecha);     //puede ser days month years y hasta -1 month menos un mes...
           $Fechae=date("Y-m-d",$nDias);
           if($Otnva[fechae] < $Fechae ){   //Checa y autaliza de fecha de entrada
              $Otnva=mysql_query("update otnvas set fechae = '$Fechae' WHERE usr='$Usr' and venta='$Vta'",$link);
           }
        }else{
           $Msj="El Estudio [$Estudio] no existe, favor de verificar";
        }
     }else{
        $Msj="Aun no as elegido la Institucion(lista/precios), favor de verificar";
     }
  }elseif($op=="br"){  // borra todo
     $Fecha=date("Y-m-d");
     $lUp=mysql_query("delete from otdnvas WHERE usr='$Usr' and venta=$Vta",$link);
     $lUp=mysql_query("delete from otnvas WHERE usr='$Usr' and venta=$Vta",$link);
     $InsA=mysql_query("SELECT lista from inst WHERE institucion='1'",$link);    #Checo la lista de la institucion
     $Ins=mysql_fetch_array($InsA);
     $lUp=mysql_query("insert into otnvas (inst,lista,usr,venta,servicio,fechae) VALUES ('1','$Ins[0]','$Usr','$Vta','Ordinaria','$Fecha')",$link);
  }elseif($op=="cl"){ //CLiente
     $CliA=mysql_query("SELECT cliente from cli WHERE cliente='$_REQUEST[Cliente]'",$link);
     if($Cli=mysql_fetch_array($CliA)){
         $lUp=mysql_query("update otnvas set cliente = '$_REQUEST[Cliente]' WHERE usr='$Usr' and venta='$Vta'",$link);
     }else{
         $Msj="El No. de paciente ".$Cliente." no existe";
     }
  }elseif($op=="md"){ //Med
     $Medico=strtoupper($_REQUEST[Medico]);
     $MedA=mysql_query("SELECT medico from med WHERE medico='$Medico'",$link);
     if($Med=mysql_fetch_array($MedA)){
        $lUp=mysql_query("update otnvas set medico = '$Medico' WHERE usr='$Usr' and venta='$Vta'",$link);
     }else{
        $Msj="La Clave del Medico ".$Medico ." no existe";
     }
  }elseif($op=="Eli"){ //Elimina     $Medico=strtoupper($Medico);
    $lUp=mysql_query("delete from otdnvas WHERE usr='$Usr' and venta='$Vta' and estudio='$busca' limit 1",$link);
  }elseif($op=="Rc"){
    $lUp=mysql_query("update otnvas set receta = '$_REQUEST[Receta]',fechar='$_REQUEST[Fechar]',abono=$_REQUEST[Abono] WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="dtg"){ //Aplica el descuento gral.
    $lUp=mysql_query("update otdnvas set descuento = '$_REQUEST[Descuento]' WHERE usr='$Usr' and venta='$Vta'",$link);
    $lUp=mysql_query("update otnvas set descuento = '$_REQUEST[Razon]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="dt"){ //Aplica el descuento detalle por estudio
    $lUp=mysql_query("update otdnvas set descuento = '$_REQUEST[Descuento]' WHERE usr='$Usr' and venta='$Vta' and estudio='$Estudio'",$link);
    $lUp=mysql_query("update otnvas set descuento = '$_REQUEST[Razon]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Sv"){
    $lUp=mysql_query("update otnvas  set servicio='$_REQUEST[Servicio]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Fc"){   // Cambai fecha de etrega
    $lUp=mysql_query("update otnvas set fechae = '$_REQUEST[Fechae]' WHERE usr='$Usr' and venta='$Vta'",$link);

  }elseif($op=="Fn"){               // Genera la Orden de trabajo
  
    $Fecha  = date("Y-m-d");
     $Hora1 = date("H:i");         
     $Hora2 = strtotime("-60 min",strtotime($Hora1));
     $hora  = date("H:i",$Hora2);

//    $hora   = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30
    $OtdA   = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otdnvas WHERE usr='$Usr' and venta='$Vta'");
    $Otd    = mysql_fetch_array($OtdA);
    $OtA    = mysql_query("SELECT * FROM otnvas WHERE usr='$Usr' AND venta='$Vta'",$link);
    $Ot     = mysql_fetch_array($OtA);

    $Abono  = $_REQUEST[Abono];

    $cPag   = 'No';
    
    if($Abono + .5 >= $Otd[0]){$cPag='Si';}

	 $lUp = mysql_query("INSERT INTO ot 
          (cliente,fecha,hora,medico,fecharec,fechae,institucion,diagmedico,observaciones,servicio,recepcionista,
          receta,importe,descuento,pagada,fecpago,medicon,status)
          VALUES 
          ('$Ot[cliente]','$Fecha','$hora','$Ot[medico]','$_REQUEST[Fechar]','$Ot[fechae]','$Ot[inst]',
          '$_REQUEST[Diagmedico]','$_REQUEST[Observaciones]','$Ot[servicio]','$Usr','$_REQUEST[Receta]',$Otd[0],
          '$Ot[descuento]','$cPag','$Fecha','$_REQUEST[Medicon]','DEPTO')");

    $Id   = mysql_insert_id();
	
	 $lUp  = mysql_query("UPDATE cli SET numveces=numveces+1 WHERE cliente='$Ot[cliente]' LIMIT 1");

    $lUpA = mysql_query("SELECT otdnvas.estudio,otdnvas.precio,otdnvas.descuento,est.depto 
            FROM otdnvas,est 
            WHERE usr='$Usr' and venta='$Vta' and otdnvas.estudio=est.estudio");    #Checo k bno halla estudios capturados

    $lBd  = false;

    while($lUp=mysql_fetch_array($lUpA)){
          $Depto  = 'DEPTO'; //kda en el depto
          $PreA   = mysql_query("SELECT estudio,pre FROM pred WHERE estudio='$lUp[0]'");  //Si existe en preanaliticos
          while($lPre=mysql_fetch_array($PreA)){  //Si tiene pregts.de pre-analitico los da de alta en otpre
 		  	        $lBd=true;
                 //$Depto='PRE-A';   Se van directos al Depto ya no pasan por pre-analiticos
                //$ExiA=mysql_query("SELECT pregunta from otpre WHERE orden='$Id' and pregunta='$lPre[1]'",$link);  //Si existe en preanaliticos
                //if(!$lEx=mysql_fetch_array($ExiA)){ //Si no ex.la preg la agrga
                $lEx=mysql_query("INSERT INTO otpre (orden,estudio,pregunta) VALUES ($Id,'$lUp[0]','$lPre[1]')");
                //}
    	   }

         $lOtd = mysql_query("INSERT INTO otd (orden,estudio,precio,descuento,status) VALUES ($Id,'$lUp[estudio]','$lUp[precio]','$lUp[descuento]','$Depto')",$link);
        
         if( $lUp[depto] == 2 ){                  // Si es que es de radiologia se crea un archivo en base a un formato del word y lo copio
         
             $FilWord = strtolower("informes/".$lUp[estudio].".doc")         ;
             $FilOut  = strtolower("textos/".$lUp[estudio].$Id.".doc");

             if (file_exists($FilWord)) {
 						 copy($FilWord,$FilOut);
            }         

         }
         
    }

    if($Abono>0){$nAb=$Abono;}else{$nAb=.5;}

    $Tpago = $_REQUEST[Tpago];

    $lUp   = mysql_query("INSERT INTO cja (orden,fecha,hora,usuario,importe,tpago) 
    		    VALUES 
    		    ($Id,'$Fecha','$hora','$Usr','$nAb','$Tpago')",$link);

    //mysql_close($link);

    header("Location: impot.php?busca=$Id&Vta=$Vta");

  }

  $cSql = "SELECT estudio,descripcion,precio,descuento 
  			 FROM otdnvas 
          WHERE usr='$Usr' and venta='$Vta'";

  $Edit = array("Estudio","Descripcion","Precio","%Dto","Importe","Elim","-","-","-","-","-","-");

  $aPrg = array('Ninguno','Cliente frecuente','Diabetes','Chequeo medico','Otro');

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF" onload="cFocus()">

<?php headymenu($Titulo,0); ?>

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
<table align='center' width="100%" border="0" background='lib/fondo.gif' >
  <tr>
    <td width="220">
    
      <?php

        echo "<form name='form7' method='post' action='ordenesnvas.php?op=pr'>";
        $OtA=mysql_query("SELECT inst,lista,cliente,medico,receta,fechar,observaciones,diagmedico,servicio,fechae,abono from otnvas WHERE venta=$Vta and usr='$Usr'",$link);
        $Ot=mysql_fetch_array($OtA);
        $InsA=mysql_query("SELECT institucion,nombre,alias from inst order by institucion",$link);
        $MedA=mysql_query("SELECT nombrec from med WHERE medico='$Ot[medico]'",$link);
        $Med=mysql_fetch_array($MedA);
        $CliA=mysql_query("SELECT nombrec,programa,observaciones from cli WHERE cliente='$Ot[cliente]'");
        $Cli=mysql_fetch_array($CliA);
        echo "<font color='#0000CC' size='3'>VENTA :</font><font size='4'> [<a class='pg' href='ordenesnvas.php?Vta=1'>1</a>";
        echo "<a class='pg' href='ordenesnvas.php?Vta=2'> 2</a> <a class='pg' href='ordenesnvas.php?Vta=3'>3</a>&nbsp;<a class='pg' href='ordenesnvas.php?Vta=4'>4</a>&nbsp;<a class='pg' href='ordenesnvas.php?Vta=5'>5</a>]<font color='#66CC66' size='3'> $Linea[$Vta]</FONT>";
        echo "</form>";

        ?>

    </td>
    <td width="378">
      <?php
        echo "<form name='form1' method='post' action='ordenesnvas.php?op=pr'>";
        echo "<font color='#0000CC' size='2' face='Verdana, Arial, Helvetica, sans-serif'>INSTITUCION: ";
        echo "<SELECT name='Institucion'>";
        while ($Ins=mysql_fetch_array($InsA)){
            echo "<option value=$Ins[0]> $Ins[0] $Ins[alias]</option>";
            if($Ins[0]==$Ot[inst]){$DesIns=$Ins[alias];}
            if($Ins[0]==1){$DesInsD=$Ins[alias];}
        }
        if($Ot[inst]==''){
            echo "<option SELECTed>1&nbsp;$DesInsD</option>";
        }else{
            echo "<option SELECTed>$Ot[inst]&nbsp;$DesIns</option>";
        }
        echo "</SELECT>";
        echo "&nbsp; <input type='submit' name='Submit' value='Enter'>";
        echo "<input type='hidden' name='Vta' value=$Vta>";
        echo "</FONT></form>";
		?>
    </td>
    <td width="290">
      <?php
        echo "<form name='form8' method='post' action='ordenesnvas.php?op=Sv'>";
        if($Ot[servicio]==""){$DisSer="Ordinaria";}else{$DisSer=$Ot[servicio];}
        echo "<font color='#0000CC' size='2' face='Verdana, Arial, Helvetica, sans-serif'>SERVICIO: ";
        echo "<SELECT name='Servicio'>";
        echo "<option value='Ordinaria'>Ordinaria</option>";
        echo "<option value='Urgente'>Urgente</option>";
        echo "<option value='Express'>Express</option>";
        echo "<option value='Hospitalizado'>Hospitalizado</option>";
        echo "<option value='Nocturno'>Nocturno</option>";
        echo "<option SELECTed value=$DisSer>$DisSer</option>";
        echo "</SELECT>";
        echo "&nbsp;&nbsp;<input type='submit' name='Submit' value='Enter'>";
        echo "</font></form>";
		?>
    </td>
  </tr>
  <tr>
    <td height='40'><font color="#0000CC" size="2" face="Verdana, Arial, Helvetica, sans-serif">Lista
      de precio: <?php echo $Ot[lista]; ?></font></td>
    <td> <form name='form9' method='post' action='ordenesnvas.php?op=Fc&Vta=<?php echo $Vta;?>'>
        <font color="#0000CC" size="2" face="Verdana, Arial, Helvetica, sans-serif">Fecha/entrega:</font>
        <input type="text" name="Fechae" value ='<?php echo $Ot[fechae]; ?>' maxlength="10" size="10">
        &nbsp;&nbsp;
        <input type='submit' name='Submit' value='Enter'>
      </form></td>
    <td><div align="center"><font color="#0000CC" size="2" face="Verdana, Arial, Helvetica, sans-serif">
        <a class="pg" href="descuentos.php?Vta=<?php echo $Vta;?>&op=dtg">[Dto/Gral]</a> &nbsp; &nbsp;
        <a class="pg" href="ordenesnvas.php?Vta=<?php echo $Vta;?>&Usr=<?php echo $Usr;?>&op=br">[Borra/todo]</a>
        </font></div></td>
  </tr>
  <tr> 
    <td>
     <form name='form2' method='post' action='ordenesnvas.php?op=Estudio&Vta=<?php echo $Vta;?>'>
        <font color="#0000CC" size="1"><strong>ESTUDIO</strong>: </font>
        <input name='Estudio' type='text' size='5' >
        <a href='agrestord.php?Vta=<?php echo $Vta?>&Usr=<?php echo $Usr;?>'><img src='lib/lupa_o.gif' alt='Busca en el catalogo de estudios' border='0'></a>
      </form>
    </td>
    <td>
      <?php
        echo "<form name='form3' method='post' action='ordenesnvas.php?op=cl&Vta=$Vta'>";
            $nPrg=$Cli[programa]; 
            echo "<font color='#0000CC' size='1'><strong>PACIENTE</strong> :</font>";
            echo "<input name='Cliente' type='text' value='$Ot[cliente]' size='5'>";
            echo "&nbsp; <a href='clientesord.php?Vta=$Vta'><img src='lib/lupa_o.gif' border='0'></a>";
            echo "&nbsp;<font color='#0000CC' size='1'>".substr($Cli[nombrec],0,35)."</font>";
            echo "<br>Programa: $aPrg[$nPrg] &nbsp; &nbsp; ";
            if($Cli[observaciones]<>''){
               //echo "<a href=''></a>";
               echo "<a href=javascript:winuni('cliobs.php?busca=$Ot[cliente]')><img src='lib/int4.gif' border='0'></a>";               
            }
        echo "</form>";
      ?>  
    </td>
    <td>
      <form name='form4' method='post' action='ordenesnvas.php?Vta=<? echo $Vta;?>&op=md'>
        <font color='#0000CC' size='1'><strong>MEDICO</strong>. :</font>
        <input name='Medico' type='text' value='<?php echo $Ot[medico]; ?>' size='5'>
        <a href='medicos.php'><img src='lib/lupa_o.gif' alt='Catalogo de medicos' border='0'></a>
        <font color='#0000CC' size='1'><?php echo substr($Med[nombrec],0,45);?>
        </font> </p>
      </form>
    </td>
  </tr>
</table>

  <?php

  echo "$Gfont &nbsp; &nbsp; <a class='pg' href='ordenes.php?Vta=$Vta'>Regresar</a> &nbsp; &nbsp; &nbsp; &nbsp; ";

  echo "<a class='pg' href=javascript:Ventana('cotizacion.php?Vta=$Vta')>Cotizacion</a> </font>";

  if(!$res=mysql_query($cSql.$cWhe,$link)){
        cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes
  }else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------
		  
		  if($limitInf < 0){$limitInf=0;}

        $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

        $res=mysql_query($sql,$link);

        PonEncabezado();         #---------------------Encabezado del browse----------------------

        while($registro=mysql_fetch_array($res)){?>
           <tr bgcolor="#CEDADD" onMouseOver="this.style.backgroundColor='#E3DBFD';this.style.cursor='hand';" onMouseOut="this.style.backgroundColor='#CEDADD'"o"];">
           <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b> &nbsp; <? echo $registro[estudio]; ?></b></font></td>
           <td><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b>&nbsp;<? echo $registro[descripcion]; ?></b></font></td>
           <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[precio],"2"); ?>&nbsp;</b></font></td>
           <td align='right'><a href="descuentos.php?Vta=<?php echo $Vta;?>&op=dt&Estudio=<?php echo $registro[estudio];?>"><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo $registro[descuento]; ?></b></font></a></td>
           <td align='right'><font size="2" face="Verdana, Arial, Helvetica, sans-serif" color="#0066FF"><b><? echo number_format($registro[precio]*(1-$registro[descuento]/100),"2"); ?></b></font></td>
           <td align='center'><a href=<?php echo 'ordenesnvas.php?op=Eli&busca='.$registro[estudio].'&Vta='.$Vta; ?>><img src='lib/dele.png' alt='Elimina' border='0'></a></td>
           </tr>
           <?
           $nImp=$nImp+($registro[precio]*(1-($registro[descuento]/100)));
           $nRng++;
        }//fin while

        echo "<tr><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td align='right'>$Gfont <b> $ Importe &nbsp; </b></td><td align='right'>$Gfont <b> ".number_format($nImp,'2')." </b></font></td><td>&nbsp;</td></tr>";

        PonPaginacion(false);      #-------------------pon los No.de paginas-------------------

        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if


    echo "<br>";
    echo "<hr noshade style='color:66CC66;height:5px'>";
    echo "<br>";

    echo "<form name='formNva' method='post' action='ordenesnvasd.php?Vta=$Vta&op=md&Abono=$nImp' onSubmit='return Completo();'>";
        echo "<input type='submit' name='Genera' value='Genera Orden de Estudios'>";
    echo "</form>";

    mysql_close();

    ?>
     </div>
  </form>
<td width="416" valign="top">
</td>
</body>
</html>