<?php

  session_start();

  include_once ("auth.php");
  include_once ("authconfig.php");
  include_once ("check.php");
  require("lib/kaplib.php");

  $link=conectarse();

  $tamPag=15;

  $op=$_REQUEST[op];

  $Usr=$check['uname'];

  $Depto=$_REQUEST[Depto];

  $busca=$_REQUEST[Orden];

  $pagina=$_REQUEST[pagina];

  $estudio=$_REQUEST[Estudio];

  if($op=="gu"){  //Guarda el Movto de Notas

         $lUp=mysql_query("UPDATE otd SET texto = '$_REQUEST[Texto]',status='TERMINADA' WHERE orden='$busca' AND estudio='$estudio' limit 1");
         
         echo "<script language='javascript'>setTimeout('self.close();',100)</script>";

  }

  $Tabla="ot";

  $Titulo="Estudio por departamento";

  $EstA=mysql_query("SELECT descripcion,proceso FROM est WHERE estudio='$estudio'",$link);

  $Est=mysql_fetch_array($EstA);

  $OtA=mysql_query("SELECT ot.fecha,ot.hora,ot.fechae,ot.servicio,ot.cliente,ot.medico,ot.diagmedico,ot.observaciones,

  inst.nombre,cli.sexo,cli.nombrec,cli.fechan,med.nombrec as nombremed,otd.texto 
  
  FROM ot,inst,cli,med,otd 
  
  WHERE  ot.orden='$busca' AND ot.cliente=cli.cliente AND ot.institucion = inst.institucion AND ot.medico=med.medico 

  AND otd.orden=ot.orden AND otd.estudio='$estudio'");

  $Ot=mysql_fetch_array($OtA);

  $lAg=$Nombre<>$Cpo[nombre];

  $Fecha=date("Y-m-d");

  require ("config.php");

?>

<html>
<head>
<title><?php echo $Titulo;?></title>
<!-- TinyMCE -->
<script language="javascript" type="text/javascript" src="/tinymce/jscripts/tiny_mce/tiny_mce.js"></script>
<script language="javascript" type="text/javascript">
	tinyMCE.init({
		mode : "textareas",
		theme : "advanced",
		plugins : "table,save,advhr,advimage,advlink,emotions,iespell,insertdatetime,preview,zoom,media,searchreplace,print,contextmenu,paste,directionality,fullscreen",
		theme_advanced_buttons1_add_before : "save,newdocument,separator",
		theme_advanced_buttons1_add : "fontselect,fontsizeselect",
		theme_advanced_buttons2_add : "separator,insertdate,inserttime,preview,zoom,separator,forecolor,backcolor",
		theme_advanced_buttons2_add_before: "cut,copy,paste,pastetext,pasteword,separator,search,replace,separator",
		theme_advanced_buttons3_add_before : "tablecontrols,separator",
		theme_advanced_buttons3_add : "emotions,iespell,media,advhr,separator,print,separator,ltr,rtl,separator,fullscreen",
		theme_advanced_toolbar_location : "top",
		theme_advanced_toolbar_align : "left",
		theme_advanced_statusbar_location : "bottom",
		content_css : "example_word.css",
	    plugi2n_insertdate_dateFormat : "%Y-%m-%d",
	    plugi2n_insertdate_timeFormat : "%H:%M:%S",
		external_link_list_url : "example_link_list.js",
		external_image_list_url : "example_image_list.js",
		media_external_list_url : "example_media_list.js",
		file_browser_callback : "fileBrowserCallBack",
		paste_use_dialog : false,
		theme_advanced_resizing : true,
		theme_advanced_resize_horizontal : false,
		theme_advanced_link_targets : "_something=My somthing;_something2=My somthing2;_something3=My somthing3;",
		paste_auto_cleanup_on_paste : true,
		paste_convert_headers_to_strong : false,
		paste_strip_class_attributes : "all",
		paste_remove_spans : false,
		paste_remove_styles : false		
	});

	function fileBrowserCallBack(field_name, url, type, win) {
		// This is where you insert your custom filebrowser logic
		alert("Filebrowser callback: field_name: " + field_name + ", url: " + url + ", type: " + type);

		// Insert new URL, this would normaly be done in a popup
		win.document.forms[0].elements[field_name].value = "someurl.htm";
	}
</script>

<!-- /TinyMCE -->

</head>
<body bgcolor="#FFFFFF">
<script language="JavaScript1.2">
function cFocus(){
  document.form1.Nombre.focus();
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form1.Nombre.value=document.form1.Nombre.value.toUpperCase();}
}
</script>

    <table align='center' border='2' width='50%' cellspacing= '3' cellpading = '0'><tr><td background='lib/bar.jpg' align='center'><font size='2' face='verdana' color='#6D6D6D'><?php echo "[ $estudio - $Est[descripcion] ]"; ?></font></td></tr></table>

    <div align='center'><font size='2' face='verdana' color='#6D6D6D'>
     <font color="#0066FF" size="2" >No.Orden : <?php echo $busca;?></font> &nbsp;
     <font color='#0066FF' size='2' >Fecha: </font>&nbsp;<?php echo $Ot[fecha];?> &nbsp;
     <font color='#0066FF' size='2'>Hora :</FONT> &nbsp;<?php echo $Ot[hora];?> &nbsp;
     <font color='#0066FF' size='2'>Fec/Ent :</FONT>&nbsp; <?php echo $Ot[fechae]?> &nbsp;
     <font color='#0066FF' size='2'>Tpo/servicio :</FONT><?php echo $Ot[servicio];?>
     </div><p align='center'>
     <font color='#0066FF' size='2' >Paciente : </FONT><?php echo $Ot[cliente]." - ".substr($Ot[nombrec],0,17);?>&nbsp;&nbsp;&nbsp;&nbsp;
     <font color='#0066FF' size='2'>Inst.:</FONT> <?php echo $Ot[nombre];?> &nbsp; &nbsp;
     <font color='#0066FF' size='2'>Med.: </FONT> <?php echo $Ot[medico]." - ".substr($Ot[nombremed],0,15);?>
     </p>
     <div align='center'>
     <font color='#0066FF' size='2' >Sexo : </FONT><?php echo $Ot[sexo];?> &nbsp; &nbsp;
     <font color='#0066FF' size='2'>Edad :</FONT> <?php echo $Fecha-$Ot[fechan]; ?>
     A&ntilde;os </div>

<br><br>

<?php

echo "<table align='center' width='50%' border='2' cellspacing= '3' cellpading = '0'><tr><td background='lib/bar.jpg' align='center'><font size='2' face='verdana' color='#6D6D6D' >Pre-analiticos</font></td></tr></table>";

$OtpreA = mysql_query("SELECT cue.pregunta,otpre.nota,cue.id,cue.tipo 
          FROM otpre,cue 
          WHERE otpre.orden='$busca' AND otpre.estudio='$estudio' AND cue.id=otpre.pregunta",$link);

    echo "<table align='center' width='100%'>";
    echo "<tr><td align='right'>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td></tr>";
    $Sec=1;
    while($registro=mysql_fetch_array($OtpreA)){
 		echo "<tr><td align='right'>$Gfont $registro[0] $Gfon </td><td>&nbsp;</td>";
        echo "<td>";
        $Campo="Nota".ltrim($Sec);
     	  if($registro[3]=="Si/No"){
   	          echo "<select name='$Campo'>";
                echo "<option value='Si'>Si</option>";
                echo "<option value='No'>No</option>";
                echo "<option selected>$registro[1]</option>";
                echo "</select>";
        }elseif($registro[3]=="Fecha"){
   	          echo "<input name='$Campo' value ='$registro[1]' type='text' size='8' >";
        }else{
   	          echo "<input name='$Campo' value ='$registro[1]' type='text' size='80' >";
                //echo "<TEXTAREA NAME='$Campo' cols='50' rows='3' >$registro[1]</TEXTAREA>";
        }
   	  $Sec=$Sec+1;
   	  echo "</td></tr>";
    }
    echo "</table>";
    echo "<input type='hidden' name='estudio' value=$estudio>";
    echo "<input type='hidden' name='Depto' value=$Depto>";
    echo "<input type='hidden' name='op' value=pr>";

    //echo Botones();

    echo "</form><br>";

    echo "<form name='form3' method='get' action='impword.php'>";
    
          echo "<textarea name='Texto' rows='20' cols='80' style='width: 100%'>";
          echo "$Ot[texto]";
          echo "</textarea>";


          echo "<input type='hidden' name='estudio' value=$estudio>";
          echo "<input type='hidden' name='Depto' value=$Depto>";

          echo "<input type='hidden' name='op' value='gu'>"; // Resultdos

          echo Botones();


    echo "</form>";

    ?>

    <p>&nbsp;</p>

    </td>

   </tr>

</table>

</body>

</html>

<?

mysql_close();

?>