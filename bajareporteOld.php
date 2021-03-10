<?php
    require("lib/kaplib.php");
    $link=conectarse();
  	$Pr=mysql_query("select pregunta,variable,tipo,longitud from reportesd where id='$busca' order by orden",$link);
	if (($Prg=mysql_fetch_array($Pr)) and $lBd==""){
	    echo "<form name='form1' method='get' action='bajareporte.php'>";
                 echo "<p><font color='#0066FF' size='2'> $Prg[pregunta] </font>";
                 echo "<input type='text' name='$Prg[variable]' size='$Prg[longitud]' ></p>";
                 while ($Prg=mysql_fetch_array($Pr)){
                           echo "<p><font color='#0066FF' size='3'> $Prg[pregunta] </font>";
                           echo "<input type='text' name='$Prg[variable]' size='$Prg[longitud]' ></p>";
                 }
                 echo "<input type='hidden' name='lBd' value='ok'>";
                 echo "<input type='hidden' name='busca' value='$busca'>";
                 echo "<input type='submit' name='submit' value='Manda/Report' class='botones'>";
  	    echo "</form>";
	}else{
	  $Cp=mysql_query("select variable from reportesd where id='$busca' order by orden",$link);
   	  $result=mysql_query("select instruccion,nombre from reportes where id='$busca'",$link);
	  $ins=mysql_fetch_array($result);
	  $nombre=$ins[1].".xls";
	  $cSql="select ".$ins[0];
      while ($Cps=mysql_fetch_array($Cp)){
        $cCpo=$Cps[variable];
         $cSql=str_replace($cCpo,$$cCpo,$cSql);
	  }
  	  $cSql=str_replace('$','',$cSql);
      //echo $cSql;
	  //$lUp=mysql_query($ins[0],$link);   DISEÑO ANTERIOR CREANDO UN TXT
      //$fp = fopen($nombre,"w");
      //while ($row=mysql_fetch_array($lUp)){
	  //   fwrite($fp,"$row[0];$row[1];$row[2]\r\n");
	  //}
      //fclose($fp);
	  //$ElFichero=$nombre;
      //$TheFile = basename($ElFichero);
      //header( "Content-Type: application/octet-stream");
      //header( "Content-Length: ".filesize($ElFichero));
      //header( "Content-Disposition: attachment; filename=".$TheFile."");
      //readfile($ElFichero);
	  $result = mysql_query($cSql,$link);
	  $count = mysql_num_fields($result);
      for ($i = 0; $i < $count; $i++){
        $header .= strtoupper(mysql_field_name($result, $i))."\t";
      }
      while($row = mysql_fetch_row($result)){
        $line = '';
	    foreach($row as $value){
	    if(!isset($value) || $value == ""){
    	  $value = "\t";
	    }else{
    	  $value = str_replace('"', '""', $value);
	      $value = '"' . $value . '"' . "\t";
	    }
	    $line .= $value;
	  }
	  $data .= trim($line)."\n";
	}
	$data = str_replace("\r", "", $data);

	if ($data == "") {
	  $data = "\nLa tabla se encuentra vacia, Registros no encontrados\n";
	}
	header("Content-type: application/octet-stream");
	header("Content-Disposition: attachment; filename=$nombre");
	header("Pragma: no-cache");
	header("Expires: 0");
	echo $header."\n".$data;
}
?>