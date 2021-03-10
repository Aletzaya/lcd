<?php
  session_start();

  require("lib/lib.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  $link       = conectarse();
  $busca      = $_REQUEST[busca];

  $Tabla      = "invl";

  $Titulo     = "Detalle por producto";

  $Usr    = $check['uname'];

  $filtro    = $_REQUEST[filtro];       
  $filtro3    = $_REQUEST[filtro3];       
  $filtro5    = $_REQUEST[filtro5];       
  $filtro7    = $_REQUEST[filtro7]; 
  
  if($_REQUEST[Boton] == Aceptar or $_REQUEST[Boton] == Aplicar ){        //Para agregar uno nuevo
  
        $DepA=mysql_query("SELECT departamento FROM depd WHERE subdepto='$_REQUEST[Subdepto]'");

        $Dep=mysql_fetch_array($DepA);
		
      if($busca=='NUEVO'){

         //$Costopza =  $_REQUEST[Costo]/$_REQUEST[Pzasmedida];
         $lUp = mysql_query("INSERT INTO $Tabla (clave,descripcion,presentacion,pzasmedida,marca,iva,depto,uso,min,max,subdepto,status,presentacionp,npruebas,suc1,suc2,suc3,suc4,metodo,proveedor1,proveedor2,proveedor3,referencia,pertenece,ctrl) VALUES ('$_REQUEST[Clave]','$_REQUEST[Descripcion]','$_REQUEST[Presentacion]','$_REQUEST[Pzasmedida]','$_REQUEST[Marca]','$_REQUEST[Iva]','$Dep[0]','$_REQUEST[Uso]','$_REQUEST[Min]','$_REQUEST[Max]','$_REQUEST[Subdepto]','$_REQUEST[Status]','$_REQUEST[Presentacionp]','$_REQUEST[Npruebas]','$_REQUEST[Suc1]','$_REQUEST[Suc2]','$_REQUEST[Suc3]','$_REQUEST[Suc4]','$_REQUEST[Metodo]','$_REQUEST[Proveedor1]','$_REQUEST[Proveedor2]','$_REQUEST[Proveedor3]','$_REQUEST[Referencia]','$_REQUEST[Pertenece]','$_REQUEST[ctrl]')");

        $busca = mysql_insert_id();

       }else{

        if($Usr=='nazario' or $Usr=='SEBASTIAN' or $Usr=='sebastian' or $Usr=='Sebastian' or $Usr=='tato' or $Usr=='TATO'){

          $existencias=$_REQUEST[Invgral]+$_REQUEST[Invmatriz]+$_REQUEST[Invtepex]+$_REQUEST[Invhf]+$_REQUEST[Invreyes]+$_REQUEST[Invgralreyes];

          $lUp = mysql_query("UPDATE $Tabla SET clave='$_REQUEST[Clave]',descripcion='$_REQUEST[Descripcion]',presentacion='$_REQUEST[Presentacion]',pzasmedida='$_REQUEST[Pzasmedida]',marca='$_REQUEST[Marca]',iva='$_REQUEST[Iva]',depto='$Dep[0]',subdepto='$_REQUEST[Subdepto]',uso='$_REQUEST[Uso]',min='$_REQUEST[Min]',max='$_REQUEST[Max]',status='$_REQUEST[Status]',presentacionp='$_REQUEST[Presentacionp]',npruebas='$_REQUEST[Npruebas]',suc1='$_REQUEST[Suc1]',suc2='$_REQUEST[Suc2]',suc3='$_REQUEST[Suc3]',suc4='$_REQUEST[Suc4]',metodo='$_REQUEST[Metodo]',proveedor1='$_REQUEST[Proveedor1]',proveedor2='$_REQUEST[Proveedor2]',proveedor3='$_REQUEST[Proveedor3]',referencia='$_REQUEST[Referencia]',invgral='$_REQUEST[Invgral]',invmatriz='$_REQUEST[Invmatriz]',invtepex='$_REQUEST[Invtepex]',invhf='$_REQUEST[Invhf]',invreyes='$_REQUEST[Invreyes]',invgralreyes='$_REQUEST[Invgralreyes]',existencia='$existencias',pertenece='$_REQUEST[Pertenece]',ctrl='$_REQUEST[ctrl]' WHERE id='$busca' limit 1");

        }else{

          $lUp = mysql_query("UPDATE $Tabla SET clave='$_REQUEST[Clave]',descripcion='$_REQUEST[Descripcion]',presentacion='$_REQUEST[Presentacion]',pzasmedida='$_REQUEST[Pzasmedida]',marca='$_REQUEST[Marca]',iva='$_REQUEST[Iva]',depto='$Dep[0]',subdepto='$_REQUEST[Subdepto]',uso='$_REQUEST[Uso]',min='$_REQUEST[Min]',max='$_REQUEST[Max]',status='$_REQUEST[Status]',presentacionp='$_REQUEST[Presentacionp]',npruebas='$_REQUEST[Npruebas]',suc1='$_REQUEST[Suc1]',suc2='$_REQUEST[Suc2]',suc3='$_REQUEST[Suc3]',suc4='$_REQUEST[Suc4]',metodo='$_REQUEST[Metodo]',proveedor1='$_REQUEST[Proveedor1]',proveedor2='$_REQUEST[Proveedor2]',proveedor3='$_REQUEST[Proveedor3]',referencia='$_REQUEST[Referencia]',pertenece='$_REQUEST[Pertenece]',ctrl='$_REQUEST[ctrl]' WHERE id='$busca' limit 1");


        }
       }

      if($_REQUEST[Boton] == Aceptar){
         header("Location: invlab.php?orden=invl.descripcion&Sort=Desc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Fech2=&Fech3=&estudio=&suc=");
      }

  }elseif($_REQUEST[Boton] == Cancelar){

         header("Location: invlab.php?orden=invl.descripcion&Sort=Desc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Fech2=&Fech3=&estudio=&suc=");

  }else{

      $cSql="SELECT * FROM $Tabla WHERE id='$busca'";

  }


  $CpoA = mysql_query($cSql);
  $Cpo  = mysql_fetch_array($CpoA);
  
 if($busca=='NUEVO'){$lAg=true;}  
 //$lAg=$_REQUEST[Apellidop]<>$Cpo[apellidop];
  

 $Fecha = date("Y-m-d");
 
 require ("config.php");

?>
<html>

<head>

<title><?php echo $Titulo;?></title>

</head>

<body onLoad="cFocus1()">

<link type="text/css" rel="stylesheet" href="lib/dhtmlgoodies_calendar.css?random=90051112" media="screen"></link>

<SCRIPT type="text/javascript" src="lib/dhtmlgoodies_calendar.js?random=90090518"></script>

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">
function cFocus1(){
  document.form2.Apellidop.focus();
}
function cFocus2(){
  document.form1.Fechan.focus();
}
function SiElimina(){
  if(confirm("ATENCION! Desea dar de Baja este registro?")){
     return(true);
   }else{
     document.form1.busca.value="NUEVO";
      return(false);
   }
}


function Completo(){
var lRt;
lRt=true;
if(document.form2.Apellidom.value==""){lRt=false;}
if(document.form2.Apellidop.value==""){lRt=false;}
if(document.form2.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
document.form1.Apellidom.value=document.form2.Apellidom.value
document.form1.Apellidop.value=document.form2.Apellidop.value
document.form1.Nombre.value=document.form2.Nombre.value
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Apellidop'){document.form2.Apellidop.value=document.form2.Apellidop.value.toUpperCase();
}if (cCampo=='Apellidom'){document.form2.Apellidom.value=document.form2.Apellidom.value.toUpperCase();
}if (cCampo=='Nombre'){document.form2.Nombre.value=document.form2.Nombre.value.toUpperCase();
}if (cCampo=='Direccion'){document.form1.Direccion.value=document.form1.Direccion.value.toUpperCase();
}if (cCampo=='Localidad'){document.form1.Localidad.value=document.form1.Localidad.value.toUpperCase();
}if (cCampo=='Telefono'){document.form1.Telefono.value=document.form1.Telefono.value.toUpperCase();
}if (cCampo=='Municipio'){document.form1.Municipio.value=document.form1.Municipio.value.toUpperCase();
}if (cCampo=='Rfc'){document.form1.Rfc.value=document.form1.Rfc.value.toUpperCase();}
}
</script>

<table width="100%" border="0">
    <?php
    echo "<tr>";

      echo "<td  width='5%'>";
      
         echo "<a href='invlab.php?orden=invl.descripcion&Sort=Desc&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Fech2=&Fech3=&estudio=&suc='><img src='lib/regresa.jpg' border='0'></a><p>&nbsp;</p>";

      echo "</td>";
      
      echo "<td align='center'> $Gfont ";          

       echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";
	 
              cTable('70%','0'); 
   
              cInput("Clave:","Text","30","Clave","right",$Cpo[clave],"30",true,false,'codigo de barras');

              cInput("Referencia:","Text","12","Referencia","right",$Cpo[referencia],"12",true,false,'');
		
              cInput('Descripcion:','text','40','Descripcion','right',$Cpo[descripcion],'50',false,false,'');

              cInput('Marca:','text','20','Marca','right',$Cpo[marca],'20',false,false,'');
                            
              echo "<tr><td align='right'>$Gfont Presentacion individual: &nbsp; </td><td>";
              echo "<SELECT name='Presentacion'>";
              echo "<option value='Piezas'>Piezas</option>";
              echo "<option value='Cajas'>Cajas</option>";
              echo "<option value='Paquetes'>Paquetes</option>";
              echo "<option value='Bolsas'>Bolsas</option>";
              echo "<option value='Kilos'>Kilos</option>";
              echo "<option value='Litros'>Litros</option>";
              echo "<option value='Frasco'>Frasco</option>";
              echo "<option selected value='$Cpo[presentacion]'>$Cpo[presentacion]</option>";
              echo "</SELECT>";
              echo "</td></tr>";

              cInput('Pzas.por presentacion:','text','10','Pzasmedida','right',$Cpo[pzasmedida],'10',false,false,'');

              cInput('Presentacion del producto:','text','100','Presentacionp','right',$Cpo[presentacionp],'100',false,false,'');

              cInput('No. de pruebas:','text','9','Npruebas','right',$Cpo[npruebas],'9',false,false,'');

              cInput('% de Iva:','text','10','Iva','right',$Cpo[iva],'10',false,false,'');
		
              cInput('Existencia:','text','10','Existencia','right',$Cpo[existencia],'10',false,true,'');
              
              cInput('Costo:','text','10','Costo','right',$Cpo[costo],'10',false,true,'');

              $costopza=($Cpo[costo]*(($Cpo[iva]/100)+1))/$Cpo[npruebas];

             // cInput('Costo por pieza:','text','10','Costopza','right',$Cpo[costopza],'10',false,true,'');
              cInput('Costo por pieza:','text','10','Costopza','right',$costopza,'10',false,true,'');
			  

      		  echo "<tr><td align='right'>$Gfont Departamento $Gfon</td><td align='left'>$Gfont";

			  $DepA=mysql_query("SELECT dep.nombre FROM dep,depd WHERE depd.subdepto='$Cpo[subdepto]' and depd.departamento=dep.departamento and '$Cpo[subdepto]'<>'' ");
			  $Dep=mysql_fetch_array($DepA);
			  
			  echo "<strong> $Dep[0] &nbsp;&nbsp;</strong> ";

			  echo "$Gfon</td></tr>";

			  echo "<tr><td align='right'>$Gfont Sub-departamento $Gfon</td><td align='left'>";
		
			  //$cSub=mysql_query("SELECT subdepto FROM depd");
			  $cSub=mysql_query("SELECT depd.subdepto,dep.nombre FROM dep,depd WHERE dep.departamento=depd.departamento");
			  
			  echo "<SELECT name='Subdepto'>";
			  while ($dep=mysql_fetch_array($cSub)){
					 echo "<option value='$dep[0]'>$dep[1]: $dep[0]</option>";
			  }
			  echo "<option SELECTed value='$Cpo[subdepto]'>$Cpo[subdepto]</option>";
			  echo "</SELECT>";
			 
        cInput('Utilizacion:','text','40','Uso','right',$Cpo[uso],'40',false,false,'');


        if($Usr=='nazario' or $Usr=='SEBASTIAN' or $Usr=='sebastian' or $Usr=='Sebastian' or $Usr=='tato' or $Usr=='TATO'){

            cInput('Exist.Inv.Gral.:','text','10','Invgral','right',$Cpo[invgral],'10',false,false,'');

            cInput('Exist.Inv.Matriz:','text','10','Invmatriz','right',$Cpo[invmatriz],'10',false,false,'');

            cInput('Exist.Inv.Tepex:','text','10','Invtepex','right',$Cpo[invtepex],'10',false,false,'');

            cInput('Exist.Inv.Futura:','text','10','Invhf','right',$Cpo[invhf],'10',false,false,'');

            cInput('Exist.Inv.Gral.Reyes:','text','10','Invgralreyes','right',$Cpo[invgralreyes],'10',false,false,'');

            cInput('Exist.Inv.Reyes:','text','10','Invreyes','right',$Cpo[invreyes],'10',false,false,'');

            cInput('Existencia Gral:','text','10','Existencia','right',$Cpo[existencia],'10',false,true,'');

        }else{

            cInput('Exist.Inv.Gral.:','text','10','Invgral','right',$Cpo[invgral],'10',false,true,'');

            cInput('Exist.Inv.Matriz:','text','10','Invmatriz','right',$Cpo[invmatriz],'10',false,true,'');

            cInput('Exist.Inv.Tepex:','text','10','Invtepex','right',$Cpo[invtepex],'10',false,true,'');

            cInput('Exist.Inv.Futura:','text','10','Invhf','right',$Cpo[invhf],'10',false,true,'');

            cInput('Exist.Inv.Gral.Reyes:','text','10','Invgralreyes','right',$Cpo[invgralreyes],'10',false,true,'');

            cInput('Exist.Inv.Reyes:','text','10','Invreyes','right',$Cpo[invreyes],'10',false,true,'');

            cInput('Existencia Gral:','text','10','Existencia','right',$Cpo[existencia],'10',false,true,'');

        }

       
        cInput('Minimo:','text','10','Min','right',$Cpo[min],'10',false,false,'');

        cInput('Maximo:','text','10','Max','right',$Cpo[max],'10',false,false,'');

        cInput('Metodo:','text','50','Metodo','right',$Cpo[metodo],'50',false,false,'');

        cInput('Proveedor 1:','text','50','Proveedor1','right',$Cpo[proveedor1],'50',false,false,'');
        cInput('Proveedor 2:','text','50','Proveedor2','right',$Cpo[proveedor2],'50',false,false,'');
        cInput('Proveedor 3:','text','50','Proveedor3','right',$Cpo[proveedor3],'50',false,false,'');

        echo "<tr><td align='right'>$Gfont Sucursales: &nbsp;";
        echo "</td><td>";
			 
        echo "<select size='1' name='Suc1' class='Estilo10'>";
        $SucA1=mysql_query("SELECT id,alias FROM cia order by id");
        while($Suc1=mysql_fetch_array($SucA1)){
          echo "<option value=$Suc1[id]> $Suc1[id]&nbsp;$Suc1[alias]</option>";
          if($Suc1[id]==$Cpo[suc1]){$DesSuc1=$Suc1[alias];}
        }
        echo "<option selected value=$Cpo[suc1]>$Gfont <font size='-1'>$Cpo[suc1] $DesSuc1</option></p>";     
        echo "</select>";

        echo "<select size='1' name='Suc2' class='Estilo10'>";
        $SucA2=mysql_query("SELECT id,alias FROM cia order by id");
        while($Suc2=mysql_fetch_array($SucA2)){
          echo "<option value=$Suc2[id]> $Suc2[id]&nbsp;$Suc2[alias]</option>";
          if($Suc2[id]==$Cpo[suc2]){$DesSuc2=$Suc2[alias];}
        }
        echo "<option selected value=$Cpo[suc2]>$Gfont <font size='-1'>$Cpo[suc2] $DesSuc2</option></p>";     
        echo "</select>";

        echo "<select size='1' name='Suc3' class='Estilo10'>";
        $SucA3=mysql_query("SELECT id,alias FROM cia order by id");
        while($Suc3=mysql_fetch_array($SucA3)){
          echo "<option value=$Suc3[id]> $Suc3[id]&nbsp;$Suc3[alias]</option>";
          if($Suc3[id]==$Cpo[suc3]){$DesSuc3=$Suc3[alias];}
        }
        echo "<option selected value=$Cpo[suc3]>$Gfont <font size='-1'>$Cpo[suc3] $DesSuc3</option></p>";     
        echo "</select>";

        echo "<select size='1' name='Suc4' class='Estilo10'>";
        $SucA4=mysql_query("SELECT id,alias FROM cia order by id");
        while($Suc4=mysql_fetch_array($SucA4)){
          echo "<option value=$Suc4[id]> $Suc4[id]&nbsp;$Suc4[alias]</option>";
          if($Suc4[id]==$Cpo[suc4]){$DesSuc4=$Suc4[alias];}
        }
        echo "<option selected value=$Cpo[suc4]>$Gfont <font size='-1'>$Cpo[suc4] $DesSuc4</option></p>";     
        echo "</select>";

        echo "<tr><td align='right'>$Gfont Almacen Principal: &nbsp;";
        echo "</td><td>";
        echo "<select name='Pertenece'>";
        echo "<option value='General'>General</option>";
        echo "<option value='Reyes'>Reyes</option>";
        echo "<option selected value=$Cpo[pertenece]>$Cpo[pertenece]</option>";
        echo "</select>";
        echo "</td></tr>";

			  echo "<tr><td align='right'>$Gfont Status: &nbsp;";
			  echo "</td><td>";
			  echo "<select name='Status'>";
			  echo "<option value='Activo'>Activo</option>";
			  echo "<option value='Inactivo'>Inactivo</option>";
			  echo "<option selected value=$Cpo[status]>$Cpo[status]</option>";
			  echo "</select>";
			  echo "</td></tr>";

        echo "<tr><td align='right'>$Gfont Control Inventario: &nbsp;";
        echo "</td><td>";
        echo "<select name='ctrl'>";
        echo "<option value='Pendiente'>Pendiente</option>";
        echo "<option value='Revision'>Revision</option>";
        echo "<option value='Verificado'>Verificado</option>";
        echo "<option selected value=$Cpo[ctrl]>$Cpo[ctrl]</option>";
        echo "</select>";
        echo "</td></tr>";

    
              cTableCie();
              
              echo Botones();

              mysql_close();
              
    echo "</form>";
  echo "</td>";
  echo "</tr>";
echo "</table>";
echo "</body>";
echo "</html>";
?>