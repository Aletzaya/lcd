<?php

  session_start();

  if(isset($_REQUEST[cVar])){$_SESSION[cVarVal]=$_REQUEST[cVar];}

  require("lib/filtro.php");
  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");

  require("lib/lib.php");

  $Usr      = $check['uname'];
  $link     = conectarse();
  $pagina   = $_REQUEST[pagina];
  $op       = $_REQUEST[op];

  $Fecha  = date("Y-m-d H:i:s");

  $busca    = $_SESSION[cVarVal];

  if($_REQUEST[Boton] == Agregar){

    $lUp   = mysql_query("INSERT INTO elealtpdf2 (estudio,descripcion,tipo,unidad,longitud,decimales,id,min,max,nota,vlogico,vtexto,alineacion,celda1,celda2,celda3,celdas,valref,idvalor1,parentesis1,valor1,operador1,idvalor2,valor2,parentesis2,operador2,idvalor3,valor3,parentesis3,operador3,idvalor4,valor4,parentesis4,calculo,condiciona) 
          VALUES 
          ('$busca','$_REQUEST[Descripcion]','$_REQUEST[Tipo]','$_REQUEST[Unidad]','$_REQUEST[Longitud]','$_REQUEST[Decimales]','$_REQUEST[Id]','$_REQUEST[Min]','$_REQUEST[Max]','$_REQUEST[Nota]','$_REQUEST[Vlogico]','$_REQUEST[Vtexto]','$_REQUEST[Alineacion]','$_REQUEST[Celda1]','$_REQUEST[Celda2]','$_REQUEST[Celda3]','$_REQUEST[Celdas]','$_REQUEST[Valref]','$_REQUEST[Idvalor1]','$_REQUEST[Parentesis1]','$_REQUEST[Valor1]','$_REQUEST[Operador1]','$_REQUEST[Idvalor2]','$_REQUEST[Valor2]','$_REQUEST[Parentesis2]','$_REQUEST[Operador2]','$_REQUEST[Idvalor3]','$_REQUEST[Valor3]','$_REQUEST[Parentesis3]','$_REQUEST[Operador3]','$_REQUEST[Idvalor4]','$_REQUEST[Valor4]','$_REQUEST[Parentesis4]','$_REQUEST[Calculo]','$_REQUEST[Condiciona]')");

      $lUp2   = mysql_query("UPDATE elealtpdf2 SET fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

      $lUp3  = mysql_query("INSERT INTO logelealtpdf2 (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Agrego elemento $_REQUEST[Id]')");

  }elseif($_REQUEST[Boton] == Actualizar){
    
      $lUp   = mysql_query("UPDATE elealtpdf2 SET descripcion='$_REQUEST[Descripcion]',tipo='$_REQUEST[Tipo]',unidad='$_REQUEST[Unidad]',
               longitud='$_REQUEST[Longitud]',decimales='$_REQUEST[Decimales]',id='$_REQUEST[Id]',
               min='$_REQUEST[Min]',max='$_REQUEST[Max]',nota='$_REQUEST[Nota]',vlogico='$_REQUEST[Vlogico]',vtexto='$_REQUEST[Vtexto]',alineacion='$_REQUEST[Alineacion]',celda1='$_REQUEST[Celda1]',celda2='$_REQUEST[Celda2]',celda3='$_REQUEST[Celda3]',celdas='$_REQUEST[Celdas]',valref='$_REQUEST[Valref]',idvalor1='$_REQUEST[Idvalor1]',parentesis1='$_REQUEST[Parentesis1]',valor1='$_REQUEST[Valor1]',operador1='$_REQUEST[Operador1]',idvalor2='$_REQUEST[Idvalor2]',valor2='$_REQUEST[Valor2]',parentesis2='$_REQUEST[Parentesis2]',operador2='$_REQUEST[Operador2]',idvalor3='$_REQUEST[Idvalor3]',valor3='$_REQUEST[Valor3]',parentesis3='$_REQUEST[Parentesis3]',operador3='$_REQUEST[Operador3]',idvalor4='$_REQUEST[Idvalor4]',valor4='$_REQUEST[Valor4]',parentesis4='$_REQUEST[Parentesis4]',calculo='$_REQUEST[Calculo]',condiciona='$_REQUEST[Condiciona]'
               WHERE idnvo='$_REQUEST[Up]'");

      $lUp2   = mysql_query("UPDATE elealtpdf2 SET fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

      $lUp3  = mysql_query("INSERT INTO logelealtpdf2 (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Actualizo elemento $_REQUEST[Id]')");


  }elseif($op=='Si'){                    //Elimina Registro

     $lUp   = mysql_query("DELETE FROM elealtpdf2 WHERE estudio='$busca' AND id='$_REQUEST[cId]' LIMIT 1");
     $Msj   = "El registro: $_REQUEST[cId] a sido dado de baja";

      $lUp3  = mysql_query("INSERT INTO logelealtpdf2 (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$busca','Elimina elemento $_REQUEST[cId]')");

  }elseif($op=='cerrar'){

          $lUp   = mysql_query("UPDATE elealtpdf2 SET bloqueado='Si', fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logelealtpdf2 (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Estudio')");



  }elseif($op=='abrir'){

          $lUp   = mysql_query("UPDATE elealtpdf2 SET bloqueado='No', fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logelealtpdf2 (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Estudio')");

  }


  $Estudio  = $_REQUEST[busca];

  $OrdenDef = "id";            //Orden de la tabla por default

  $tamPag   = 13;

  $cSql     = "SELECT * FROM elealtpdf2 WHERE estudio = '$busca'";

  $Tabla    = "elealtpdf2";

  $Titulo   = "Elementos del estudio Alterno II: [$busca]";

  $Edit     = array("Edit","Secuencia","Descripcion","Tipo","Longitud","No.Decimales","Min","Max","Elim",
              "-","Nele.ido","Cele.descripcion","Cele.tipo","Cele.longitud","Cele.decimales","Cele.min","Cele.max");

  require ("config.php");


?>

<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">

<html>

<head>

<title><?php echo $Titulo;?></title>

<?php headymenu($Titulo,1);

  filtro($Tabla);           #---------------Si trae algo del filtro realizalo ----------------


  if($_REQUEST[op]=='sm'){

     $cSum=mysql_query("select sum($_REQUEST[SumaCampo]) from $Tabla ".$cWhe,$link);

     $Suma=mysql_fetch_array($cSum);

     $cFuncion=" // --> $SumaCampo: ".number_format($Suma[0],"2");

  }

  if(!$res=mysql_query($cSql.$cWhe,$link)){

      cMensaje("No se encontraron resultados ò hay un error en el filtro");    #Manda mensaje de datos no existentes

  }else{

        CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

        $sql=$cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
        $res=mysql_query($sql,$link);

        PonEncabezado();         #---------------------Encabezado del browse----------------------
       $bloqueado='No';
		while($registro=mysql_fetch_array($res)){
        if($registro[tipo]=="c"){$cTt="Caracter";
        }elseif($registro[tipo]=="d"){$cTt="Fecha";
        }elseif($registro[tipo]=="n"){$cTt="Numerico";
        }elseif($registro[tipo]=="t"){$cTt="Texto";
        }elseif($registro[tipo]=="l"){$cTt="Logico";
        }elseif($registro[tipo]=="v"){$cTt="Espacio *";
        }elseif($registro[tipo]=="z"){$cTt="Columnas *";
        }elseif($registro[tipo]=="e"){$cTt="Encabezado *";
        }else{$cTt="Seccion *";}
           
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid3;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
          echo "<td align='center'><a href=elementosaltpdf2.php?cEd=$registro[idnvo]&pagina=$pagina><img src='lib/edit.png' alt='Edita reg' border='0'></td>";            
           echo "<td align='center'>$Gfont $registro[id]</font></td>";
           echo "<td>$Gfont $registro[descripcion]</font></td>";
           echo "<td>$Gfont $cTt </font></td>";
           echo "<td align='center'>$Gfont $registro[unidad] $Gfon</td>";
           echo "<td align='right'>$Gfont $registro[longitud] $Gfon</td>";
           echo "<td align='right'>$Gfont ".number_format($registro[decimales],"2")."$Gfon</td>";
           echo "<td align='right'>$Gfont ".number_format($registro[min],"2")."$Gfon</td>";
           echo "<td align='right'>$Gfont ".number_format($registro[max],"2")."$Gfon</td>";
           echo "<td align='right'>$Gfont $registro[vlogico] $Gfon</td>";
           echo "<td align='right'>$Gfont $registro[vtexto] $Gfon</td>";
           $bloqueado=$registro[bloqueado];
           $usr=$registro[usr];
           $fecha=$registro[fecha];
           if($bloqueado=='No'){
              echo "<td align='center'><a href=elementosaltpdf2.php?cId=$registro[id]&op=Si&pagina=$pagina><img src='lib/deleon.png' alt='Borra reg' border='0'></td>";
            }else{
              echo "<td align='center'> - </td>";
            }
           echo "</tr>";
           $nRng++;
		}//fin while


        PonPaginacion(false);      #-------------------pon los No.de paginas-------------------


  echo " &nbsp;  &nbsp; <a class='pg' href=estudioselealtpdf2.php><img src='lib/regresa.jpg' border=0></a> &nbsp; ";
  //echo "<a class='pg' href='elementos.php?op=rp'>$Gfont Genera reporte $Gfon </a>";

  echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('resultapdf.php?clnk=$clnk&Estudio=$busca&alterno=2')><font size='1'><img src='pdfenv.png' alt='pdf' border='0'></font></a>  ";

    if($bloqueado=='No'){

        echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=elementosaltpdf2.php?busca=$busca&op=cerrar&pagina=$pagina>Cerrar <img src='images/candadoa.png' alt='pdf' border='0'></a><font size='2'> Ultima Modificacion por: $usr - $fecha </font>";

    }else{

        echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=elementosaltpdf2.php?busca=$busca&op=abrir&pagina=$pagina>Abrir <img src='images/candadoc.png' alt='pdf' border='0'></a><font size='2'> Ultima Modificacion por: $usr - $fecha </font>";
    }
    
     echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logelepdf.php?busca=$busca&alterno=2')><font size='1'> *** Modificaciones ***</a>  ";


   echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

      echo "<table align='center' width='98%' cellpadding='0' cellspacing='0' border='0'>";

      echo "<tr>";
  
      echo "<td width='50'>$Gfont </td>";

      echo "<td>$Gfont <b>";          
                    
          echo "Sec: ";

      echo "</b></td>";      
      echo "<td>$Gfont <b>";          

          echo "Desc: ";   

      echo "</b></td>";      
      echo "<td>$Gfont <b>";          
      
          echo "Tipo: ";
      
      echo "</b></td>";      
      echo "<td>$Gfont <b>";          

          echo "Unidad: ";

      echo "</b></td>";      
      echo "<td>$Gfont <b>";          
          
          echo "Long: ";

      echo "</b></td>";      
      echo "<td>$Gfont <b>";          
      
          echo "Dec: ";
      
      echo "</b></td>";      
      echo "<td>$Gfont <b>";          

          echo "V.Ref: ";

      echo "</b></td>";      
      echo "<td>$Gfont <b>";          

          echo "Min: ";
      
      echo "</b></td>";      
      echo "<td>$Gfont <b>";          

      echo "Max: ";

      echo "</b></td>";
      echo "</tr>";

      echo "<tr bgcolor='#e3e3e3' height='33'>";
      
      if($_REQUEST[cEd]<>''){
         $CpoA  = mysql_query("SELECT * FROM elealtpdf2 WHERE idnvo='$_REQUEST[cEd]'");
         $Cpo   = mysql_fetch_array($CpoA); 

          if($Cpo[tipo]=='c'){$Disp = '1.- Caracter';
          }elseif($Cpo[tipo]=='n'){$Disp='2.- Numerico';
          }elseif($Cpo[tipo]=='d'){$Disp='3.- Fecha';
          }elseif($Cpo[tipo]=='l'){$Disp='4.- Logico[Positivo/Negativo]';
          }elseif($Cpo[tipo]=='t'){$Disp='5.- Texto';
          }elseif($Cpo[tipo]=='v'){$Disp='6.- Espacio *';
          }elseif($Cpo[tipo]=='z'){$Disp='7.- Columnas *';
          }elseif($Cpo[tipo]=='e'){$Disp='8.- Encabezado *';
          }elseif($Cpo[tipo]=='s'){$Disp='9.- Seccion *';}

          if($Cpo[vlogico]=='Positivo'){$Vlogico = '1.- Positivo';
          }elseif($Cpo[vlogico]=='Negativo'){$Vlogico='2.- Negativo';
          }else{$Vlogico=$Cpo[vlogico];}

          if($Cpo[alineacion]=='right'){
            $Alineacion = '1.- Derecha';
          }elseif($Cpo[alineacion]=='left'){
            $Alineacion='2.- Izquierda';
          }elseif($Cpo[alineacion]=='center'){
            $Alineacion='3.- Centro';
//          }else{
//            $Alineacion='4.- Abajo';
          }
                  
          if($Cpo[celdas]=='Si'){
            $Celdas = '1.- Si';
          }elseif($Cpo[celdas]=='No'){
            $Celdas='2.- No';
          }

          if($Cpo[valref]=='Si'){
            $Valref = '1.- Si';
          }elseif($Cpo[valref]=='No'){
            $Valref='2.- No';
          }

            echo "<td width='50'>&nbsp;<a href='$_SERVER[PHP_SELF]' width='40'><img src='lib/regresar.gif' border='0'></td>";
      }else{

         echo "<td width='50'>$Gfont </td>";
        
      }

      echo "<td>$Gfont ";          

          //echo " id: $Cpo[id]";
                    
          echo "<input type='TEXT' name='Id' value='$Cpo[id]' size='3' maxlength='3')> &nbsp; ";

      echo "</td>";      
      echo "<td>$Gfont ";          

          echo "<input type='TEXT' name='Descripcion' value='$Cpo[descripcion]' size='40' maxlength='50')> &nbsp; ";

      echo "</td>";      
      echo "<td>$Gfont ";          

          echo "<select name='Tipo'>";
          echo "<option value='c'>1.- Caracter</option>";
          echo "<option value='n'>2.- Numerico</option>";
          echo "<option value='d'>3.- Fecha</option>";
          echo "<option value='l'>4.- Logico[Positivo/Negativo]</option>";
          echo "<option value='t'>5.- Texto</option>";
          echo "<option value='v'>6.- Espacio *</option>";
          echo "<option value='z'>7.- Columnas *</option>";
          echo "<option value='e'>8.- Encabezado *</option>";
          echo "<option value='s'>9.- Seccion *</option>";
          echo "<option selected value='$Cpo[tipo]'>$Disp</option>";
          echo "</select> &nbsp; ";
          
      echo "</td>";      
      echo "<td>$Gfont ";          

          echo "<input type='TEXT' name='Unidad' value='$Cpo[unidad]' size='15' maxlength='15')> &nbsp; ";

      echo "</td>";      
      echo "<td>$Gfont ";          

          echo "<input type='TEXT' name='Longitud' value='$Cpo[longitud]' size='2' maxlength='3')> &nbsp; ";

      echo "</td>";      
      echo "<td>$Gfont ";          

          echo "<input type='TEXT' name='Decimales' value='$Cpo[decimales]' size='2' maxlength='3')> &nbsp; ";

      echo "</td>"; 

      echo "<td align='center'>$Gfont ";          

      echo "<select name='Valref'>";
      echo "<option value='Si'>1.- Si</option>";
      echo "<option value='No'>2.- No</option>";
      echo "<option selected value='$Cpo[valref]'>$Valref</option>";
      echo "</select> &nbsp; ";

      echo "</td>"; 


      echo "<td>$Gfont ";          

          echo "<input type='TEXT' name='Min' value='$Cpo[min]' size='3' maxlength='9')> &nbsp; ";

      echo "</td>";      
      echo "<td>$Gfont ";          

          echo "<input type='TEXT' name='Max' value='$Cpo[max]' size='3' maxlength='9')> &nbsp; ";    
                   
      echo "</td>";      
      echo "<td>";

      if($bloqueado=='No'){

          if($_REQUEST[cEd]<>''){
          
              echo "<input type='submit' name='Boton' value='Actualizar'>";
              echo "<input type='hidden' name='Up' value='$_REQUEST[cEd]'>";
              echo "<input type='hidden' name='pagina' value='$pagina'>";
              echo "<input type='hidden' name='busca' value='$busca'>";

          }else {                    

              echo "<input type='submit' name='Boton' value='Agregar'>";
              echo "<input type='hidden' name='busca' value='$busca'>";
              
          }

      }

      echo "</td></tr><tr>";      
      echo "<td colspan='4'>$Gfont <b>";          

          echo "Nota: ";

      echo "</b></td>";     
      echo "<td>$Gfont <b>";          

          echo "Ref. Logica: ";

      echo "</b></td>";
      echo "<td colspan='2'>$Gfont <b>";          

          echo "Ref. Caracter: ";

      echo "</b></td>";
      echo "<td colspan='2'>$Gfont <b>";          

          echo "Alineac: (Encab/Secc) ";

      echo "</b></td>";
      echo "<td colspan='2'>$Gfont <b>";          

      echo "Condiciona:";

      echo "</b></td>";

      echo "</tr><tr bgcolor='#e3e3e3' height='33'>";   


            echo "<td colspan='4' align='center'>$Gfont ";
            echo "<TEXTAREA NAME='Nota' cols='70' rows='5' >$Cpo[nota]</TEXTAREA> &nbsp; "; 

      echo "</td>";    

      echo "<td>$Gfont";          

          echo "<select name='Vlogico'>";
          echo "<option value='Positivo'>1.- Positivo</option>";
          echo "<option value='Negativo'>2.- Negativo</option>";
          echo "<option selected value='$Cpo[vlogico]'>$Vlogico</option>";
          echo "</select> &nbsp;";

      echo "</td>";    

      echo "<td colspan='2'>$Gfont ";          

          echo "<input type='TEXT' name='Vtexto' value='$Cpo[vtexto]' size='25' maxlength='25')> &nbsp;";

            echo "</td>";    

      echo "<td colspan='2'>$Gfont ";          

          echo "<select name='Alineacion'>";
          echo "<option value='right'>1.- Derecha</option>";
          echo "<option value='left'>2.- Izquierda</option>";
          echo "<option value='center'>3.- Centro</option>";
          //echo "<option value='abajo'>4.- Abajo</option>";
          echo "<option selected value='$Cpo[alineacion]'>$Alineacion</option>";
          echo "</select> &nbsp; ";

      echo "</td>";
      echo "<td colspan='2'>$Gfont ";          

      echo "<input type='TEXT' name='Condiciona' value='$Cpo[condiciona]' size='5' maxlength='5')> &nbsp; ";
          
     echo "</td></tr></table>";

      echo "<table align='center' width='98%' cellpadding='0' cellspacing='0' border='0'>";

      echo "<tr>";

      echo "<td align='center'>$Gfont <b> Celda Izquierda </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Celda Centro </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Celda Derecha </b>";   

      echo "</td>";

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Aplicar tabla? </b>";   

      echo "</td>";

      echo "</tr>";

      echo "<tr bgcolor='#e3e3e3' height='33'>";

      echo "<td align='center'>$Gfont ";          

            echo "<TEXTAREA NAME='Celda1' cols='40' rows='5' >$Cpo[celda1]</TEXTAREA> &nbsp; "; 

      echo "</td>";          
      echo "<td align='center'>$Gfont ";          
      
            echo "<TEXTAREA NAME='Celda2' cols='40' rows='5' >$Cpo[celda2]</TEXTAREA> &nbsp; "; 

      echo "</td>";          
      echo "<td align='center'>$Gfont ";          
      
            echo "<TEXTAREA NAME='Celda3' cols='40' rows='5' >$Cpo[celda3]</TEXTAREA> &nbsp; "; 

      echo "</td>";     

      echo "<td align='center'>$Gfont ";          

      echo "<select name='Celdas'>";
      echo "<option value='Si'>1.- Si</option>";
      echo "<option value='No'>2.- No</option>";
      echo "<option selected value='$Cpo[celdas]'>$Celdas</option>";
      echo "</select> &nbsp; ";

      echo "</td>";       
      echo "</tr></table>";

      //**********************  C a l c u l o s  *******************

      echo "<table align='center' width='98%' cellpadding='0' cellspacing='0' border='0'>";

      echo "<tr>";

      echo "<td align='center'>$Gfont <b> Id / Valor (1) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> ( ) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Id o Valor (1)  </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Operacion (1) </b>";   

      echo "</td>";

      echo "</tr>";
      echo "<tr bgcolor='#e3e3e3' height='33'>";

      if($Cpo[idvalor1]=='ID'){
        $Idvalor1 = '1.- ID';
      }elseif($Cpo[idvalor1]=='VALOR'){
        $Idvalor1='2.- VALOR';
      }elseif($Cpo[idvalor1]==''){
        $Idvalor1='3.- N/A';
      }

      echo "<td align='center'>$Gfont ";          

      echo "<select name='Idvalor1'>";
      echo "<option value='ID'>1.- ID</option>";
      echo "<option value='VALOR'>2.- VALOR</option>";
      echo "<option value=''>3.- N/A</option>";
      echo "<option selected value='$Cpo[idvalor1]'>$Idvalor1</option>";
      echo "</select> &nbsp; ";

      echo "</td>"; 

      if($Cpo[parentesis1]=='('){
        $Parentesis1 = '(';
      }elseif($Cpo[parentesis1]==''){
        $Parentesis1 = 'N/A';
      } 

      echo "<td align='center'>$Gfont ";

      echo "<select name='Parentesis1'>";
      echo "<option value='('>(</option>";
      echo "<option value=''>N/A</option>";
      echo "<option selected value='$Cpo[parentesis1]'>$Parentesis1</option>";
      echo "</select> &nbsp; ";

      echo "</td>"; 

      echo "<td align='center'>$Gfont ";          

      echo "<input type='TEXT' name='Valor1' value='$Cpo[valor1]' size='8' maxlength='8')> &nbsp; ";

      echo "</td>";  

      if($Cpo[operador1]=='+'){
        $Operador1 = '(';
      }elseif($Cpo[operador1]=='-'){
        $Operador1 = '-';
      }elseif($Cpo[operador1]=='*'){
        $Operador1 = '*';
      }elseif($Cpo[operador1]=='/'){
        $Operador1 = '/';
      }elseif($Cpo[operador1]==''){
        $Operador1 = 'N/A';
      }       

      echo "<td align='center'>$Gfont ";

      echo "<select name='Operador1'>";
      echo "<option value='+'>+</option>";
      echo "<option value='-'>-</option>";
      echo "<option value='*'>*</option>";
      echo "<option value='/'>/</option>";
      echo "<option value=''>N/A</option>";
      echo "<option selected value='$Cpo[operador1]'>$Operador1</option>";
      echo "</select> &nbsp; ";

      echo "</td>";          

      echo "</tr>";
      echo "<tr>";
    
      echo "<td align='center'>$Gfont <b> Id / Valor (2) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Id o Valor (2)  </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> ( ) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Operacion (2) </b>";   

      echo "</td>";

      echo "</tr>";
      echo "<tr bgcolor='#e3e3e3' height='33'>";

      if($Cpo[idvalor2]=='ID'){
        $Idvalor2 = '1.- ID';
      }elseif($Cpo[idvalor2]=='VALOR'){
        $Idvalor2='2.- VALOR';
      }elseif($Cpo[idvalor2]==''){
        $Idvalor2='3.- N/A';
      }

      echo "<td align='center'>$Gfont ";          

      echo "<select name='Idvalor2'>";
      echo "<option value='ID'>1.- ID</option>";
      echo "<option value='VALOR'>2.- VALOR</option>";
      echo "<option value=''>3.- N/A</option>";
      echo "<option selected value='$Cpo[idvalor2]'>$Idvalor2</option>";
      echo "</select> &nbsp; ";

      echo "</td>";   

      echo "<td align='center'>$Gfont ";          

      echo "<input type='TEXT' name='Valor2' value='$Cpo[valor2]' size='8' maxlength='8')> &nbsp; ";

      echo "</td>";  

      if($Cpo[parentesis2]=='('){
        $Parentesis2 = '(';
      }elseif($Cpo[parentesis2]==')'){
        $Parentesis2 = ')';
      }elseif($Cpo[parentesis2]==''){
        $Parentesis2 = 'N/A';
      } 

      echo "<td align='center'>$Gfont ";

      echo "<select name='Parentesis2'>";
      echo "<option value='('>(</option>";
      echo "<option value=')'>)</option>";
      echo "<option value=''>N/A</option>";
      echo "<option selected value='$Cpo[parentesis2]'>$Parentesis2</option>";
      echo "</select> &nbsp; ";

      echo "</td>"; 

      if($Cpo[operador2]=='+'){
        $Operador2 = '(';
      }elseif($Cpo[operador2]=='-'){
        $Operador2 = '-';
      }elseif($Cpo[operador2]=='*'){
        $Operador2 = '*';
      }elseif($Cpo[operador2]=='/'){
        $Operador2 = '/';
      }elseif($Cpo[operador2]==''){
        $Operador2 = 'N/A';
      }       

      echo "<td align='center'>$Gfont ";

      echo "<select name='Operador2'>";
      echo "<option value='+'>+</option>";
      echo "<option value='-'>-</option>";
      echo "<option value='*'>*</option>";
      echo "<option value='/'>/</option>";
      echo "<option value='N/A'>N/A</option>";
      echo "<option selected value='$Cpo[operador2]'>$Operador2</option>";
      echo "</select> &nbsp; ";

      echo "</td>";

      echo "</tr>";
      echo "<tr>";
    
      echo "<td align='center'>$Gfont <b> Id / Valor (3) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Id o Valor (3)  </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> ( ) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Operacion (3) </b>";   

      echo "</td>";

      echo "</tr>";
      echo "<tr bgcolor='#e3e3e3' height='33'>";

      if($Cpo[idvalor3]=='ID'){
        $Idvalor3 = '1.- ID';
      }elseif($Cpo[idvalor3]=='VALOR'){
        $Idvalor3='2.- VALOR';
      }elseif($Cpo[idvalor3]==''){
        $Idvalor3='3.- N/A';
      }

      echo "<td align='center'>$Gfont ";          

      echo "<select name='Idvalor3'>";
      echo "<option value='ID'>1.- ID</option>";
      echo "<option value='VALOR'>2.- VALOR</option>";
      echo "<option value=''>3.- N/A</option>";
      echo "<option selected value='$Cpo[idvalor3]'>$Idvalor3</option>";
      echo "</select> &nbsp; ";

      echo "</td>";   

      echo "<td align='center'>$Gfont ";          

      echo "<input type='TEXT' name='Valor3' value='$Cpo[valor3]' size='8' maxlength='8')> &nbsp; ";

      echo "</td>";  

     if($Cpo[parentesis3]=='('){
        $Parentesis3 = '(';
      }elseif($Cpo[parentesis3]==')'){
        $Parentesis3 = ')';
      }elseif($Cpo[parentesis3]==''){
        $Parentesis3 = 'N/A';
      } 

      echo "<td align='center'>$Gfont ";

      echo "<select name='Parentesis3'>";
      echo "<option value='('>(</option>";
      echo "<option value=')'>)</option>";
      echo "<option value=''>N/A</option>";
      echo "<option selected value='$Cpo[parentesis3]'>$Parentesis3</option>";
      echo "</select> &nbsp; ";

      echo "</td>"; 

      if($Cpo[operador3]=='+'){
        $Operador3 = '(';
      }elseif($Cpo[operador3]=='-'){
        $Operador3 = '-';
      }elseif($Cpo[operador3]=='*'){
        $Operador3 = '*';
      }elseif($Cpo[operador3]=='/'){
        $Operador3 = '/';
      }elseif($Cpo[operador3]==''){
        $Operador3 = 'N/A';
      }       

      echo "<td align='center'>$Gfont ";

      echo "<select name='Operador3'>";
      echo "<option value='+'>+</option>";
      echo "<option value='-'>-</option>";
      echo "<option value='*'>*</option>";
      echo "<option value='/'>/</option>";
      echo "<option value='N/A'>N/A</option>";
      echo "<option selected value='$Cpo[operador3]'>$Operador3</option>";
      echo "</select> &nbsp; ";

      echo "</td>";

      echo "</tr>";
      echo "<tr>";
    
      echo "<td align='center'>$Gfont <b> Id / Valor (4) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Id o Valor (4)  </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> ( ) </b>";   

      echo "</td>";

      echo "<td align='center'>$Gfont <b> Calculo? </b>";   

      echo "</td>";

      echo "</tr>";
      echo "<tr bgcolor='#e3e3e3' height='33'>";

      if($Cpo[idvalor4]=='ID'){
        $Idvalor4 = '1.- ID';
      }elseif($Cpo[idvalor4]=='VALOR'){
        $Idvalor4='2.- VALOR';
      }elseif($Cpo[idvalor4]==''){
        $Idvalor4='3.- N/A';
      }

      echo "<td align='center'>$Gfont ";          

      echo "<select name='Idvalor4'>";
      echo "<option value='ID'>1.- ID</option>";
      echo "<option value='VALOR'>2.- VALOR</option>";
      echo "<option value=''>3.- N/A</option>";
      echo "<option selected value='$Cpo[idvalor4]'>$Idvalor4</option>";
      echo "</select> &nbsp; ";

      echo "</td>";   

      echo "<td align='center'>$Gfont ";          

      echo "<input type='TEXT' name='Valor4' value='$Cpo[valor4]' size='8' maxlength='8')> &nbsp; ";

      echo "</td>";  

     if($Cpo[parentesis4]==')'){
        $Parentesis4 = ')';
      }elseif($Cpo[parentesis4]==''){
        $Parentesis4 = 'N/A';
      } 

      echo "<td align='center'>$Gfont ";

      echo "<select name='Parentesis4'>";
      echo "<option value=')'>)</option>";
      echo "<option value=''>N/A</option>";
      echo "<option selected value='$Cpo[parentesis4]'>$Parentesis4</option>";
      echo "</select> &nbsp; ";

      echo "</td>"; 

      if($Cpo[calculo]=='Si'){
        $Calculo = '1.- Si';
      }elseif($Cpo[calculo]=='No'){
        $Calculo='2.- No';
      }
  
      echo "<td align='center'>$Gfont ";          

      echo "<select name='Calculo'>";
      echo "<option value='Si'>1.- Si</option>";
      echo "<option value='No'>2.- No</option>";
      echo "<option selected value='$Cpo[calculo]'>$Calculo</option>";
      echo "</select> &nbsp; ";

      echo "</td>";  

      echo "</tr></table>";

  echo "</form>";      


        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

}//fin if

mysql_close();

?>

</body>

</html>