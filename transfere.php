<?php

  session_start();

  require("lib/lib.php");
  
  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $link  = conectarse();

  $busca = $_REQUEST[busca];
  $Tabla = "trans";
  $Msj   = "";

  $Titulo= "Detalle de transferencia";

  $lAg   = $busca=='NUEVO';

  $cSql  = "SELECT * FROM trans WHERE id='$busca'";

  $CpoA  = mysql_query($cSql);

  $Cpo   = mysql_fetch_array($CpoA);

  $lBd   = false;
  
  $Fecha = date("Y-m-d");
 
  $Hora  = date("H:i");  
  
  $Usrorin    = $_COOKIE['USERNAME'];  

  $Usr    = $check['uname'];    

  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo

	  if($busca=="NUEVO"){
         
         $lUp   = mysql_query("INSERT INTO trans (fecha,hora,origen,destino,usrorin,usrdest,status) 
                  VALUES
                  ('$Fecha','$Hora','$_REQUEST[Origen]','$_REQUEST[Destino]',
				  '$Usrorin','$_REQUEST[Usrdest]','ABIERTA')");
                 
         $Id    = mysql_insert_id();
                 
         $lBd   = true;
    }else{
     
         $lUp = mysql_query("UPDATE trans SET origen='$_REQUEST[Origen]',destino='$_REQUEST[Destino]',fecha='$Fecha',hora='$Hora',usrdest='$_REQUEST[Usrdest]',status='$_REQUEST[Status]' WHERE id='$busca' limit 1");
                      
        $lBd   = true;

          if($Cpo[status]<>'CERRADA' and $_REQUEST[Status]=='CERRADA'){

              $ProdB  = mysql_query("SELECT * FROM trans WHERE id='$busca'");
              $Prodb=mysql_fetch_array($ProdB);

              $ProdA  = mysql_query("SELECT * FROM transd WHERE id='$busca'");

              while($Prod=mysql_fetch_array($ProdA)){

                  $InvA    = mysql_query("SELECT costo FROM invl WHERE clave='$Prod[clave]'");
                  $Inv      = mysql_fetch_array($InvA);

                  $Up       = mysql_query("UPDATE invl SET $Prodb[origen] = $Prodb[origen] - $Prod[cantidad],costo = '$Inv[costo]',$Prodb[destino] = $Prodb[destino] + $Prod[cantidad] WHERE clave='$Prod[clave]' LIMIT 1");
                  
              }

          }elseif($Cpo[status]<>'ABIERTA' and $_REQUEST[Status]=='ABIERTA'){

              $ProdB  = mysql_query("SELECT * FROM trans WHERE id='$busca'");
              $Prodb=mysql_fetch_array($ProdB);

              $ProdA  = mysql_query("SELECT * FROM transd WHERE id='$busca'");

              while($Prod=mysql_fetch_array($ProdA)){
                  
                 $Up       = mysql_query("UPDATE invl SET $Prodb[origen] = $Prodb[origen] + $Prod[cantidad],$Prodb[destino] = $Prodb[destino] - $Prod[cantidad] WHERE clave='$Prod[clave]' LIMIT 1");
                  
              }

          }

    }

  }elseif($_REQUEST[Boton] == Cancelar){    // Para dar de baja

           header("Location: transfer.php");

  }

  if( $_REQUEST[Boton] == Aceptar and $lBd){        //Para r uno nuevo
      if($busca=='NUEVO'){
         header("Location: transferd.php?busca=$Id");
      }else{
         header("Location: transfer.php");      
      }   
  }


  require ("config.php");

?>

<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body bgcolor="#FFFFFF"  onload="cFocus()">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">

function Mayusculas(cCampo){
if (cCampo=='Nombre'){documsal.form1.Nombre.value=documsal.form1.Nombre.value.toUpperCase();}
}

function cFocus(){

  documsal.form1.Nombre.focus();

}
</script>

<?php

echo "<table width='100%' border='0'>";

  echo "<tr>";

    echo "<td width='10%' align='csaler'>";

        echo "<a href='transfer.php'><img src='lib/regresar.gif' border='0'></a>";

    echo "</td><td align='center'>";

       echo "<form name='form1' method='get' action='transfere.php' onSubmit='return ValCampos();'>";
       
        		 if(!$lAg){
        		 
        		    $Fecha  = $Cpo[fecha];
        		    $Hora   = $Cpo[hora]; 
        		 
        		 }else{
        		 
    					 $Fecha  = date("Y-m-d");    		 
    					 $Hora   = date("H:i");    		 
        		 
        		 }

				 echo "<p>&nbsp;</p>";
				 
             cTable('80%','0');

             cInput("No.Transferencia: ","Text","4","Id","right",$busca,"4",false,true,'');

             cInput("Fecha:","Text","9","Fecha","right",$Fecha,"10",false,true,'');

             cInput("Hora:","Text","5","Hora","right",$Hora,"5",false,true,'');

             cInput("","Text","","","","","",false,true,'');

             echo "<tr><td align='right'>$Gfont De: &nbsp; ";
             echo "</td><td>";
             echo "<select name='Origen'>";
             echo "<option value='invgral'>General</option>";
             echo "<option value='invmatriz'>Matriz</option>";
             echo "<option value='invtepex'>Tepexpan</option>";
             echo "<option value='invhf'>HF</option>";
             echo "<option value='invgralreyes'>GralReyes</option>";
             echo "<option value='invreyes'>Reyes</option>";
             echo "<option value='invcam'>Camarones</option>";
             echo "<option selected value=$Cpo[origen]>$Cpo[origen]</option>";
             echo "</td></tr>";

             cInput("","Text","","","","","",false,true,'');

             echo "<tr><td align='right'>$Gfont Para: &nbsp; ";
             echo "</td><td>";
             echo "<select name='Destino'>";
			       echo "<option value='invgral'>General</option>";
             echo "<option value='invmatriz'>Matriz</option>";
             echo "<option value='invtepex'>Tepexpan</option>";
             echo "<option value='invhf'>HF</option>";
             echo "<option value='invgralreyes'>GralReyes</option>";
             echo "<option value='invreyes'>Reyes</option>";
             echo "<option value='invcam'>Camarones</option>";

             echo "<option selected value=$Cpo[destino]>$Cpo[destino]</option>";
             echo "</td></tr>";
			 
             cInput("","Text","","","","","",false,true,'');
			 
             cInput("Recibe: ","Text","40","Usrdest","right",$Cpo[usrdest],"40",false,false,'');
			 
             cInput("Importe $: ","Text","12","Importe","right",number_format($Cpo[costo],'2'),"12",false,true,'');

          echo "<tr><td align='right'>$Gfont <b>Status: </b>&nbsp; </td><td>";

          if($Cpo[status]<>'CERRADA' or $Usr=='nazario'){
                  echo "<select name='Status'>";
                  echo "<option value='ABIERTA'>ABIERTA</option>";
                  echo "<option value='CERRADA'>CERRADA</option>";
                  echo "<option selected value='$Cpo[status]'>$Cpo[status]</option>";

                  cTableCie();
                  echo Botones();
 
          }else{
              echo "<select name='Status' disabled>";
              echo "<option value='ABIERTA'>ABIERTA</option>";
              echo "<option value='CERRADA'>CERRADA</option>";
              echo "<option selected value='$Cpo[status]'>$Cpo[status]</option>";
              echo "</selected>";
              echo "</td></tr>"; 

              cTableCie();
          }    


             mysql_close();

            echo "</font>";

      echo "</form>";

      echo "</td><td width='10%'>&nbsp;</td>";

  echo "</tr>";

echo "</table>";

CierraWin();

echo "</body>";

echo "</html>";

?>