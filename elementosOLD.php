<?php

  session_start();

  if(!isset($_REQUEST[busca])){$busca=$_SESSION['cVarVal'];}else{$_SESSION['cVarVal']=$_REQUEST[busca];$busca=$_REQUEST[busca];}

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  //$busca=$_REQUEST[busca];

  $op=$_REQUEST[op];

  $Estudio=$busca;

  if($op=='Si'){

     $lUp=mysql_query("delete from ele where estudio='$busca' and id='$_REQUEST[cId]' limit 1",$link);
     $Msj="El registro: $_REQUEST[cId] a sido dado de baja";

  }elseif($op=="rp"){			//Genera el archivo para imprimir los resultados

     $Reporte="informes/".$Estudio.".php";

     if (file_exists($Reporte)) {

         $Msj="Lo siento! el archivo [$Reporte] ya existe";

         $busca=$Estudio;

     } else {

      $EstA=mysql_query("select descripcion from est where estudio='$Estudio'",$link);

	  $Est=mysql_fetch_array($EstA);

	  $Tit="<title>".$Est[descripcion]."</title>\n";

      $fp = fopen($Reporte,"w"); //abrimos el archivo para escritura

      fwrite($fp, "<?php\n");

      fwrite($fp, "session_start();\n");

      fwrite($fp, "^Estudio=^_REQUEST[Estudio];\n");

      fwrite($fp, "^Orden=^_REQUEST[Orden];\n");

      fwrite($fp, "require('../lib/kaplib.php');\n");

      fwrite($fp, "^link=conectarse();\n");

      fwrite($fp, "^Fecha=date(\"Y-m-d\");\n");

      //fwrite($fp, "_ResA=mysql_query('select * from resul where orden='_Orden' and estudio='_Estudio' order by id);\n");

      fwrite($fp,"^EstA=mysql_query(\"select descripcion from est where estudio='^Estudio' \",^link);\n");

	  fwrite($fp,"^Est=mysql_fetch_array(^EstA);\n");

      fwrite($fp,"^EleA=mysql_query(\"select * from ele where estudio='^Estudio' order by id\",^link);\n");

      fwrite($fp,"^OtA=mysql_query(\"select ot.medico,cli.nombrec,cli.sexo,cli.fechan,med.nombrec,ot.medicon,ot.institucion,ot.diagmedico,cli.afiliacion from ot,cli,med where ot.orden='^Orden' and ot.cliente=cli.cliente and ot.medico=med.medico\",^link);\n");

	  fwrite($fp,"^Ot=mysql_fetch_array(^OtA);\n");

	  fwrite($fp, "^Edad=^Fecha-^Ot[3];\n");

      fwrite($fp, "?>\n");

      fwrite($fp, "<html>\n");

      fwrite($fp, "<head>\n");

      fwrite($fp, "<title>$Est[descripcion]</title>\n");

      fwrite($fp, "</head>\n");

      fwrite($fp, "<body>\n");

      fwrite($fp, "<table width='90%' border='0'>\n");

	  fwrite($fp,"<tr>\n");

	  fwrite($fp,"<td width='40%'>&nbsp;</td>\n");

	  fwrite($fp,"<td>\n");

	  fwrite($fp,"PACIENTE :<?php echo \"^Ot[1] &nbsp;&nbsp;&nbsp; EDAD: ^Edad a&ntilde;os. &nbsp;&nbsp;&nbsp; SEXO: ^Ot[2]\"; ?><br><br>\n");

	  fwrite($fp,"MEDICO :<?php if(^Ot[0]=='MD'){echo \"^Ot[0] &nbsp;&nbsp; ^Ot[5]\";}else{echo \"^Ot[0] &nbsp;&nbsp; ^Ot[4]\";} ?><br><br>\n");

	  fwrite($fp,"ORDEN :<?php echo \"^Ot[6]-^Orden &nbsp;&nbsp; ^Ot[8] &nbsp;&nbsp; ^Ot[7] &nbsp;&nbsp; FECHA: ^Fecha \"; ?><br><br>\n");

	  fwrite($fp,"</td>\n");

	  fwrite($fp,"</tr>\n");

	  fwrite($fp,"</table>\n");

      fwrite($fp,"<p>&nbsp;</p>");

      fwrite($fp,"<p align='center'><font size='4'><strong>$Estudio - $Est[0]</strong></font></p>\n");

      fwrite($fp,"<p>&nbsp;</p>\n");

      fwrite($fp, "<table width='75%' border='0'>\n");

      $EleA=mysql_query("select * from ele where estudio='$Estudio' order by id",$link);

      fwrite($fp, "<tr>\n");
      fwrite($fp, "<td width='20%'>&nbsp;</td>\n");
      fwrite($fp, "<td width='30%'>&nbsp;</td>\n");
      fwrite($fp, "<td width='20%'>&nbsp;</td>\n");
      fwrite($fp, "<td width='10%'>&nbsp;</td>\n");
      fwrite($fp, "<td width='10%'>&nbsp;</td>\n");
      fwrite($fp, "<td width='10%'>&nbsp;</td>\n");
	  fwrite($fp, "</tr>\n");

	  while ($Ele=mysql_fetch_array($EleA)){

	     fwrite($fp, "<?php\n");
	     fwrite($fp, "^Ele=mysql_fetch_array(^EleA);\n");
	     fwrite($fp, "^Id=^Ele[id];\n");
	     fwrite($fp, "^ResA=mysql_query(\"select c,d,n,l,t from resul where orden='^Orden' and estudio='^Estudio' and elemento='^Id' \",^link);\n");
   	     fwrite($fp, "^Res=mysql_fetch_array(^ResA);\n");
	     fwrite($fp, "?>\n");


	  	 fwrite($fp,"<tr>\n");

		 $cCpo=$Ele[tipo];

		 fwrite($fp,"<td >&nbsp;</td>\n");

		 fwrite($fp,"<td align='right'>$Ele[descripcion]  &nbsp; &nbsp;</td>\n");


		 if($Ele[tipo]=='n'){
		   fwrite($fp,"<td align='right'><?php echo number_format(^Res[$cCpo],'$Ele[decimales]'); ?></td>\n");
		 }elseif($Ele[tipo]=='l'){
		   fwrite($fp,"<td align='right'><?php if(^Res[$cCpo]=='S'){echo \"POSITIVO\";}else{echo \"NEGATIVO\";}; ?></td>\n");
		 }else{
		   fwrite($fp,"<td align='right'><?php echo \"^Res[$cCpo]\"; ?></td>\n");
    	 }

		 fwrite($fp,"<td>&nbsp;</td>\n");

		 fwrite($fp,"<td>&nbsp;</td>\n");

		 fwrite($fp,"<td>&nbsp;</td>\n");

		 fwrite($fp,"</tr>\n");

	   }

	   fwrite($fp,"</table>\n");

	   fwrite($fp,"<p>&nbsp;</p>\n");

	   fwrite($fp,"<p>&nbsp;</p>\n");

	   //fwrite($fp, "<form name='form1' method='post' action='../menu.php'>\n");

	   fwrite($fp, " <input type='submit' name='Impresion' value='Impresion' onCLick='print()'>\n");

		//fwrite($fp, "</form>\n");

		fwrite($fp, "</body>\n");

		fwrite($fp, "</html>\n");

   		fwrite($fp, "<?php\n");

   		fwrite($fp, "mysql_close();\n");

   		fwrite($fp, "?>\n");

    	fclose($fp); //cerramos la conexión y liberamos la memoria

    	//chmod($Reporte, 0777);

    	header("Location: estudiosele.php?Msj=El estudio $busca ha sido creado con exito!!!");

    }

  }

  $Estudio=$busca;

  $OrdenDef="id";            //Orden de la tabla por default

  $tamPag=15;

  $cSql="select * from ele where estudio = '$Estudio'";

  $Tabla="ele";

  $Titulo="Elementos analizar del estudio: [$busca]";

  $Edit = array("Edit","Secuencia","Descripcion","Tipo","Longitud","No.Decimales","Elim","-","Nele.ido","Cele.descripcion","Cele.tipo","Cele.longitud","Cele.decimales");

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

		while($registro=mysql_fetch_array($res)){
   		  if($registro[tipo]=="c"){$cTt="Caracter";}elseif($registro[tipo]=="d"){$cTt="Fecha";}elseif($registro[tipo]=="n"){$cTt="Numerico";}elseif($registro[tipo]=="t"){$cTt="Texto";}else{$cTt="Logico";}
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a href=elementose.php?busca=$busca&Estudio=$registro[estudio]&Id=$registro[id]><img src='lib/edit.png' alt='Edita reg' border='0'></td>";
           echo "<td align='center'>$Gfont $registro[id]</font></td>";
           echo "<td>$Gfont $registro[descripcion]</font></td>";
           echo "<td>$Gfont $cTt </font></td>";
           echo "<td align='right'>$Gfont $registro[longitud] $Gfon</td>";
           echo "<td align='right'>$Gfont ".number_format($registro[decimales],"2")."$Gfon</td>";
           echo "<td align='center'><a href=elementos.php?busca=$busca&cId=$registro[id]&op=El&pagina=$pagina><img src='lib/deleon.png' alt='Borra reg' border='0'></td>";
           echo "</tr>";
           $nRng++;
		}//fin while

        PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

        //CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    mysql_close();

    echo "<div align='right'><a class='pg' href='elementos.php?op=rp'>$Gfont Genera reporte $Gfon </a></div>";
    echo "<br> &nbsp;  &nbsp; <a class='pg' href=estudiosele.php?pagina=$pagina> Regresar </a>";

    ?>

</body>

</html>