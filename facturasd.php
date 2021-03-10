<?php

  session_start();

  require("lib/lib.php");

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  date_default_timezone_set("America/Mexico_City");
  
  if(isset($_REQUEST[busca])){$_SESSION['cVarVal']=$_REQUEST[busca];}

  if(isset($_REQUEST[pagina])) {$_SESSION['pagina'] = $_REQUEST[pagina];}		#Pagina a editar
  if(isset($_REQUEST[Sort]))   {  $_SESSION['Sort'] = $_REQUEST[Sort];}       #Orden Asc o Desc
  if(isset($_REQUEST[orden]))  { $_SESSION['orden'] = $_REQUEST[orden];}	   #Campo por el cual se ordena
  
  #Saco los valores de las sessiones los cuales normalmente no cambian;
  $pagina   = $_SESSION[pagina];			//Ojo saco pagina de la session
  $Sort     = $_SESSION[Sort];         //Orden ascendente o descendente
  $OrdenDef = $_SESSION[orden];        //Orden de la tabla por defaults  
  
  $busca     = $_SESSION[cVarVal];
  $orden     = $_REQUEST[orden];
  $op        = $_REQUEST[op];

  $Msj       = "";
  $Fecha     = date("Y-m-d");
  $Hora      = date("h:i:s");
  $Titulo    = "Detalle de factura[$busca]";
  $Usr       = $_COOKIE['USERNAME'];
  $Suc       = $_COOKIE['TEAM'];        //Sucursal 

  $link      = conectarse();

  $Tabla     = "fcd";


  if($_REQUEST[Boton]=='Agregar'){

      $CiaA  = mysql_query("SELECT iva FROM cia LIMIT 1");
      $Cia   = mysql_fetch_array($CiaA);

      $result = mysql_query("SELECT precio,descuento,estudio FROM otd WHERE orden='$_REQUEST[Orden]'");
      while($rg = mysql_fetch_array($result)){

           $Precio   = round($rg[precio]*(1-($rg[descuento]/100)),2);
           
           $PrecioU  = round($Precio / (1+($Cia[iva]/100)),2); 
          
           $lUp      = mysql_query("INSERT INTO fcd 
                       (id,estudio,precio,descuento,orden,iva,cantidad,importe)
                       VALUES 
                       ('$busca','$rg[estudio]','$PrecioU','0','$_REQUEST[Orden]','$Cia[iva]','1','$Precio')");                            
      }
      
      Totaliza($busca);
      
  }elseif($_REQUEST[Boton] == 'Enviar'){
       
      $CiaA  = mysql_query("SELECT password,iva FROM cia WHERE id='1'");
      $Cia   = mysql_fetch_array($CiaA);
	 
      $Clave = md5($_REQUEST[Password]);
	 	      
      
      if(  $Cia[0]  == $Clave ){                
          
          $FecI    = $_REQUEST[FecI];
          $FecF    = $_REQUEST[FecF];
          $Depto   = $_REQUEST[Depto];
          $Institucion  = $_REQUEST[Institucion];

          if($Depto == '' OR $Depto=='*'){
                $result = mysql_query("SELECT otd.estudio, otd.precio,otd.descuento 
                FROM otd, est, ot
                WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF'
                AND ot.institucion='$Institucion' AND ot.orden=otd.orden
                AND  otd.estudio = est.estudio  
                "); 
          }else{
                $result = mysql_query("SELECT otd.estudio, otd.precio,otd.descuento 
                FROM otd, est, ot
                WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF' AND ot.institucion='$Institucion'
                AND ot.orden=otd.orden AND  otd.estudio = est.estudio AND est.depto='$Depto'  
                ");               
          }
      
          while($rg = mysql_fetch_array($result)){

               $Precio   = round($rg[precio]*(1-($rg[descuento]/100)),2);
           
               $PrecioU  = round($Precio / (1+($Cia[iva]/100)),2); 
          
               $lUp      = mysql_query("INSERT INTO fcd 
                           (id,estudio,precio,descuento,orden,iva,cantidad,importe)
                           VALUES 
                           ('$busca','$rg[estudio]','$PrecioU','0','99999','$Cia[iva]',
                           '1','$Precio')");                             
          }

          
          
          /*
          if($Depto == '' OR $Depto=='*'){
           $OcdA = mysql_query("SELECT otd.estudio, otd.precio*(1-(otd.descuento/100)) as precio, count(otd.orden) as cnt              
                  FROM otd, est, ot
                  WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF' AND ot.orden=otd.orden
                  AND  otd.estudio = est.estudio AND ot.institucion=$Institucion 
                  GROUP BY otd.estudio"); 
          }else{
           $OcdA = mysql_query("SELECT otd.estudio, otd.precio*(1-(otd.descuento/100)) as precio, count(otd.orden) as cnt              
                  FROM otd, est, ot
                  WHERE ot.fecha>='$FecI' AND ot.fecha<='$FecF' AND ot.orden=otd.orden
                  AND  otd.estudio = est.estudio AND est.depto='$Depto' AND ot.institucion=$Institucion 
                  GROUP BY otd.estudio"); 
              
          } 
          
          
          $nIva = $Cia[iva] / 100;
           while($rg = mysql_fetch_array($OcdA)){

              $PrecioU  = $rg[precio] / (1+$nIva); 
               
              $lUp  = mysql_query("INSERT INTO fcd (id,estudio,precio,cantidad,iva,orden)
                      VALUES ('$busca','$rg[estudio]','$PrecioU','$rg[cnt]','$Cia[iva]','99999')");                                     

           }
            */
          
           Totaliza($busca);   
           
      }else{
          
         $Msj ='Error: password ' . $Clave . " vs " . md5($_REQUEST[Password]);
      }     
       
  }elseif($_REQUEST[Boton] == 'Descto' AND $_REQUEST[Descuento] > 0){
            
      $lUp  = mysql_query("INSERT INTO fcd (id,estudio,precio,cantidad,iva,orden)
              VALUES ('$busca','descuen','$_REQUEST[Descuento]','-1','0','99999')");                                     


      Totaliza($busca);   
      
      
  }elseif($_REQUEST[Cliente] <> '') {

       $Folio   = cZeros(IncrementaFolio('fcfolio',$Suc),5);
    

	$Fecha 	 = date("Y-m-d H:i:s");

	$lUp      = mysql_query("INSERT INTO fc (cliente,fecha,status,folio,suc,usr) 
                    VALUES ('$_REQUEST[Cliente]','$Fecha','Abierta','$Folio','$Suc','$Usr')");
        
	$busca    = mysql_insert_id();

        $_SESSION['cVarVal'] = $busca;
        
        $_SESSION['cVar']    = $busca;		//LO guardo para que cuando se genere la factura y sea la misma le pnga status = cerrada

  }elseif($_REQUEST[op]=="Ag"){

    $OtA = mysql_query("SELECT descuento FROM ot WHERE orden='$busca'");
    $Ot  = mysql_fetch_array($OtA);

    $LtA = mysql_query("SELECT lista FROM inst WHERE institucion='$He[institucion]'");
    $Lt=mysql_fetch_array($LtA);
    $Lista="lt".ltrim($Lt[lista]);
    $lUp=mysql_query("SELECT estudio,$Lista FROM est WHERE estudio='$_REQUEST[Estudio]' ");
    if($cCpo=mysql_fetch_array($lUp)){
       $Estudio=strtoupper($_REQUEST[Estudio]);
//       $Estudio=strtoupper($Estudio);
       $lUp=mysql_query("insert into otd (orden,estudio,precio,status,descuento) VALUES ('$busca','$Estudio','$cCpo[$Lista]','DEPTO','$Ot[descuento]')");
       $OtdA=mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otd WHERE orden='$busca'");
       $Otd=mysql_fetch_array($OtdA);
       $lUp=mysql_query("UPDATE ot set importe='$Otd[0]' WHERE orden='$busca'");
   }else{
       $Msj="El Estudio [$Estudio] no existe, favor de verificar";
   }

  }elseif($op=='Si'){                    //Elimina Registro

      $lUp=mysql_query("DELETE FROM fcd WHERE idnvo='$_REQUEST[cId]' LIMIT 1");

      Totaliza($busca);
  
      
  }          

$cSqlH     = "SELECT fc.id,fc.folio,fc.fecha,clif.nombre,clif.rfc,fc.cantidad,fc.importe,fc.iva,fc.status
            FROM fc LEFT JOIN clif ON fc.cliente=clif.id
            WHERE fc.id='$busca'";

$cSqlD     = "SELECT fcd.orden,fcd.estudio,est.descripcion,fcd.precio,fcd.descuento,fcd.idnvo,fcd.cantidad
            FROM fcd,est
            WHERE fcd.estudio=est.estudio AND fcd.id='$busca'";

$HeA       = mysql_query($cSqlH);
$He        = mysql_fetch_array($HeA);
  
$tamPag = 14;

  
echo '<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">';

require ("config.php");

echo "<html>";

echo "<head>";

echo "<title>$Titulo</title>";

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

function Mayusculas(cCampo){
if (cCampo=='Recibio'){document.form1.Recibio.value=document.form1.Recibio.value.toUpperCase();}
}
function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=450,height=350,left=100,top=150")
}
function WinRes(url){
   window.open(url,"WinRes","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=900,height=500,left=30,top=80")
}

</script>

<?php

echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt'>";

headymenu($Titulo,0);

   echo "<br><table align='center' width='92%' cellpadding='0' cellspacing='1' border='0'>";

   echo "<tr bgcolor ='#618fa9'>";

   echo "<td>$Gfont <font color='#ffffff'>No.factura: $He[folio]</td>
   <td>$Gfont <font color='#ffffff'>Cliente: $He[nombre]&nbsp; &nbsp </td>
   <td>$Gfont <font color='#ffffff'>No.estudios: $He[cantidad]</td>";

   echo "</tr><tr bgcolor ='#E1E1E1'>";

   echo "<td>$Gfont Fecha: $He[fecha] </td>
   <td align='right'>$Gfont Importe: $ ".number_format($He[importe],"2")." &nbsp; &nbsp; Iva: $ ".number_format($He[iva],'2')." &nbsp; &nbsp; Total: $ ".number_format($He[importe]+$He[iva],'2').
   " &nbsp &nbsp &nbsp </td><td>$Gfont Status: $He[status]</td>";

   echo "</tr>";

   echo "</table>";

    //Tabla 4
    //========================Con esta tabla evito que las opciones de buscar y exportar se suba
    echo "<table  width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
    echo "<tr><td height='280' align='center' valign='top' class='texto_tablas'>";
    
                echo "<table align='center' width='95%' border='0' class='texto_tablas'>";
                echo "<tr height=25>";
                echo "<td align='left'> &nbsp &nbsp ";
                if ($He[uuid] <> '' ) {
                    echo "<font color='#990000'> Factura timbrada";
                }
                echo "</td></tr></table>";
                //Detalle;

    

                echo "<table align='center' width='95%' border='0' cellspacing='1' cellpadding='0'>";
                echo "<tr height='25' background='lib/bartit.gif'>";
                echo "<td>$Gfont No.orden</font></td>";
                echo "<td>$Gfont Estudio</font></td>";
                echo "<th>$Gfont Descripcion</font></th>";
                echo "<th>$Gfont Cnt</font></th>";
                echo "<th>$Gfont Precio</font></th>";
                echo "<th>$Gfont %Dto</font></th>";
                echo "<th>$Gfont Importe</font></th>";
                echo "<th>$Gfont Elim</font></th>";
                echo "</tr>";

                $res=mysql_query($cSql);
                        
                CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

                $sql  = $cSqlD.$cWhe." ORDER BY fcd.idnvo";
                //echo $sql;
                $res  = mysql_query($sql);

                //PonEncabezado();         #---------------------Encabezado del browse----------------------
                
                while($registro=mysql_fetch_array($res)){

                        $clnk=strtolower($registro[estudio]);

                        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

                        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
                        echo "<td>$Gfont $registro[orden]</font></td>";
                        echo "<td>$Gfont $registro[estudio]</font></td>";
                        echo "<td>$Gfont $registro[descripcion]</font></td>";
                        echo "<td align='right'>$Gfont $registro[cantidad]</font></td>";
                        echo "<td align='right'>$Gfont ".number_format($registro[precio],"2")."</font></td>";
                        echo "<td align='right'>$Gfont ".number_format($registro[descuento],"2")."</font></td>";
                        echo "<td align='right'>$Gfont ".number_format(($registro[precio] * (1-($registro[descuento]/100)))*$registro[cantidad],"2")."</font></td>";
                        //echo "<td align='center'><a class='Seleccionar' href='?cId=$registro[idnvo]'>eliminar</a></font></td>";
                        if($He[status] == 'Abierta'){
                           echo "<td align='center'><a href=javascript:confirmar('Deseas&nbsp;eliminar&nbsp;el&nbsp;registro?','$_SERVER[PHP_SELF]?cId=$registro[idnvo]&op=Si')><img src='lib/deleon.png' alt='Elimina Registro' border='0'></a></td>";
                        }else{
                           echo "<td align='center'><img src='lib/deleoff.png' alt='Elimina Registro' border='0'></td>";                            
                        }   
                        
                        echo "</tr>";
                        $nRng++;

                }//fin while

                echo "</table> <br>";

           echo "</td></tr></table>";                 
           //============Cierra de tabla 4==============
  

           
                echo "<form name='form1' method='get' action=" . $_SERVER['PHP_SELF'] . " onSubmit='return ValCampos();'>";
                echo "<table  width='100%' border='0' align='center' cellpadding='0' cellspacing='0'>";
                echo "<tr><td height='50' width='70' align='center' valign='top' class='texto_tablas'>";           
                echo " &nbsp; <a class='pg' href='facturas.php?pagina=0&Sort=Asc&orden=fc.id&busca='><img src='lib/regresa.jpg' border=0></a> ";
                echo "</td><td align='left' valign='top'>$Gfont ";

                if($He[status] == 'Abierta'){                
                    
                   echo "No. de orden trabajo: ";
                   echo "<input class='Input' type='text' name='Orden' value='' size='6'> &nbsp ";
                   echo "<input class='Botones' type='submit' name='Boton' value='Agregar'>";
                   echo " &nbsp; &nbsp; &nbsp; $Msj";

                        if ($He[importe] > 0) {
                                echo "<a  class='Cuadro_rojo' href='genfactura.php'>&nbsp;<b>CLICK AQUI PARA GENERAR LA FACTURA&nbsp;</a>";                    
                                echo " &nbsp; &nbsp; <input type='text' name='Descuento' value='' size='8'>";    
                                echo " &nbsp; &nbsp; <input type='submit' name='Boton' value='Descto'>";
                        }

                        echo "<br>";
                        if($Fechas == 1){
                          $FecI=$_REQUEST[FecI];
                          $FecF=$_REQUEST[FecF];
                        }else{
                            $FecI=date("Y-m-d");
                            $FecF=date("Y-m-d");
                        }	 

                        echo " &nbsp; Fec.I: ";
                        echo "<INPUT TYPE='TEXT' class='textos' name='FecI' size='9' value ='$FecI'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecI,'yyyy-mm-dd',this)>";
                        echo "Fec.F: ";
                        echo "<INPUT TYPE='TEXT' class='textos' name='FecF' size='9' value ='$FecF'> &nbsp; <img src='lib/calendar.png' border='0' onclick=displayCalendar(document.forms[0].FecF,'yyyy-mm-dd',this)>";
                        //echo "Institucion : ";
                        $InsA=mysql_query("select institucion,nombre from inst",$link);
                        echo "<select class='textos' name='Institucion'>";
                        echo "<option value='*'> *  T o d o s </option>";
                        while ($Ins=mysql_fetch_array($InsA)){
                            echo "<option value='$Ins[0]'>".ucwords(strtolower(substr($Ins[1],0,30)))."</option>";
                        }
                        echo "<option selected value=''>Selecciona la institucion</option>";
                        echo "</select> &nbsp ";

                        //echo "Departamento : ";
                        $Depto=mysql_query("select departamento,nombre from dep",$link);
                        echo "<select class='textos' name='Depto'>";
                        echo "<option value=''> *  T o d o s </option>";
                        while ($Depto1=mysql_fetch_array($Depto)){
                            echo "<option value='$Depto1[0]'>".ucwords(strtolower($Depto1[1]))."</option>";
                        }
                        echo "<option selected value=''>Departamente</option>";
                        echo "</select>&nbsp ";

                        echo "Passw: ";
                        echo "<input class='Input' type='password' name='Password' value='' size='12'> &nbsp ";


                        echo " <INPUT TYPE='SUBMIT' class='textos' name='Boton' value='Enviar'>";
           
                }
                
                echo "</td></tr></table>";
                
            echo "</form>";
            
           
          
echo "</body>";

echo "</html>";

function Totaliza($busca){	//busca es idnvo de medt y cVarVal es id de la entrada;

      $CiaA  = mysql_query("SELECT iva FROM cia LIMIT 1");
      $Cia   = mysql_fetch_array($CiaA);
    
     $DddA  = mysql_query("SELECT 
              round(sum(importe),2) as ImporteTotal,   
              round(sum(precio),2) as PrecioSinIva,
              sum(cantidad) as cantidad 
              FROM fcd WHERE id='$busca' and cantidad > 0");         
         
     $Ddd   = mysql_fetch_array($DddA);
   
     if($Ddd[0]==0){
        $Cnt=0;$Importe=0;$Iva=0;$Total=0;
     }else{
        $Cnt        = $Ddd[cantidad];
        $Importe    = $Ddd[PrecioSinIva];
        $Iva        = round($Ddd[PrecioSinIva] * ($Cia[iva]/100),2);
        $Total      = $Ddd[ImporteTotal];
        
     }

     //$nImporte = $Total - ($Iva + $Ieps);	//Con esto lo obligo a que me cuadre;
    $lUp   = mysql_query("UPDATE fc SET cantidad=$Cnt,importe = $Importe, iva=$Iva, total= $Total WHERE id=$busca");

}


mysql_close();
?>