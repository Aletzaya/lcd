<?php
  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/kaplib.php");

  $Usr=$check['uname'];

  $link=conectarse();

  $tamPag=15;

  $pagina=$_REQUEST[pagina];

  $busca=$_REQUEST[busca];

  $op=$_REQUEST[op];

  $Titulo="Catalogo de preguntas pre-analiticas";

  $OrdenDef="id";            //Orden de la tabla por default

  $Tabla="cue";

  if($op=='Si'){

     $lUp=mysql_query("delete from $Tabla where id='$_REQUEST[cId]' limit 1",$link);
     $Msj="El registro: $_REQUEST[cId] a sido dado de baja";

  }

  $cSql="select * from $Tabla where pregunta like '%$busca%' ";

  $Edit = array("Edit","Clave","Pregunta","Tipo","Elim","-","Nid","Cpregunta","Ctipo","-");

  require ("config.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<title><?php echo $Titulo;?></title>
</head>
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

function Ventana(url){
   window.open(url,"ventana","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=320,height=350,left=250,top=150")
}
</script>
<body bgcolor="#FFFFFF">

<?php headymenu($Titulo,1);

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
           if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

           echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
           echo "<td align='center'><a href=preguntase.php?busca=$registro[id]&pagina=$pagina><img src='lib/edit.png' alt='Edita reg' border='0'></td>";
           echo "<td>$Gfont $registro[id]</font></td>";
           echo "<td>$Gfont $registro[pregunta]</font></td>";
           echo "<td>$Gfont $registro[tipo]</font></td>";
           echo "<td align='center'><a href=preguntas.php?cId=$registro[id]&op=El&pagina=$pagina><img src='lib/deleon.png' alt='Borra reg' border='0'></td>";
           echo "<tr>";
           $nRng++;
		}//fin while

        PonPaginacion(true);      #-------------------pon los No.de paginas-------------------

        CuadroInferior($busca);    #-------------------Op.para hacer filtros-------------------

     }//fin if

    mysql_close();

    ?>

</body>

</html>