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

  $busca=$_REQUEST[busca];

  $pagina=$_REQUEST[pagina];

  $lBd=$_REQUEST[lBd];

  $Fecha=date("Y-m-d");

  $Pr=mysql_query("select pregunta,variable,tipo,longitud from reportesd where id='$busca' order by orden",$link);

  if (($Prg=mysql_fetch_array($Pr)) and $lBd==""){
          $HeA=mysql_query("select descripcion from reportes where id='$busca'",$link);
          $He=mysql_fetch_array($HeA);
           echo "<form name='form1' method='get' action='bajareporte.php'>";
                 echo "<font color='#0066FF' size='3'><div align='center'>$He[0]</div>";
                 echo "<p align='center'> $Prg[pregunta]";
                 if(strtoupper($Prg[tipo])=="D"){
                    echo "<input type='text' name='$Prg[variable]' value='$Fecha' size='11' >";
                 }else{
                    echo "<input type='text' name='$Prg[variable]' size='$Prg[longitud]' >";
                 }
                 echo "</p>";
                 while ($Prg=mysql_fetch_array($Pr)){
                           echo "<p align='center'> $Prg[pregunta] ";
                           if(strtoupper($Prg[tipo])=="D"){
                              echo "<input type='text' name='$Prg[variable]' value='$Fecha' size='11' >";
                           }else{
                              echo "<input type='text' name='$Prg[variable]' size='$Prg[longitud]' >";
                           }
                           echo "</p>";
                 }
                 echo "<input type='hidden' name='lBd' value='ok'>";
                 echo "<input type='hidden' name='busca' value='$busca'>";
                 echo "<div align='center'><input type='submit' name='submit' value='Manda reporte' class='botones'></div></font>";
          echo "</form>";
  }else{
          $Cp=mysql_query("select variable from reportesd where id='$busca' order by orden",$link);
          $result=mysql_query("select instruccion,nombre from reportes where id='$busca'",$link);
          $ins=mysql_fetch_array($result);
          $nombre=$ins[1].".xls";
          $cSql="select ".$ins[0];
          while ($Cps=mysql_fetch_array($Cp)){
              $cCpo=$Cps[variable];
              $cCpo=$_REQUEST[$cCpo];
              $cSql=str_replace($Cps[variable],$cCpo,$cSql);
          }
          $cSql=str_replace('$','',$cSql);
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