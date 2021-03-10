<?php
  session_start();

  require("lib/filtro.php");

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  include_once ("CFDIComboBoxes.php");

  require("lib/lib.php");

  $queryParameters = array();
  foreach ($_REQUEST as $key=>$value) {
      $queryParameters[$key] = $value;
  }

  date_default_timezone_set("America/Mexico_City");
  $Usr    = $check['uname'];
  
  $cSql4="SELECT team FROM authuser WHERE authuser.uname='$Usr'";
  $result4=mysql_query($cSql4);
  $row4=mysql_fetch_array($result4);
  $row4s=$row4[team];

  $fechmod= date("Y-m-d H:i:s");
  $Fecha  = date("Y-m-d H:i:s");

  $link   = conectarse();

  $tamPag = 15;

  $pagina = $_REQUEST[pagina];
  $busca  = $_REQUEST[busca];
  $op     = $_REQUEST[op];
  $opm     = $_REQUEST[opm];
  $Depto     = $_REQUEST[Depto];
  if(!isset($_REQUEST[suc])){$suc    = '1';}else{ $suc    = $_REQUEST[suc];}
  if(!isset($_REQUEST[ele])){$ele    = '1';}else{ $ele    = $_REQUEST[ele];}
  if(!isset($_REQUEST[opm])){$opm    = 'basicos';}else{ $opm    = $_REQUEST[opm];}
  $Mixtolcd = $_REQUEST[Mixtolcd];
  $Mixtotpx = $_REQUEST[Mixtotpx];
  $Mixtohf = $_REQUEST[Mixtohf];
  $Mixtomaq = $_REQUEST[Mixtomaq];
  $Mixtorys = $_REQUEST[Mixtorys];
  $Producto = $_REQUEST[Producto];                           //Numero de query dentro de la base de datos
  $idproducto = $_REQUEST[idproducto];                           //Numero de query dentro de la base de datos
  $Estudio = $_REQUEST[Estudio];                             //Numero de query dentro de la base de datos
  $cumedida       = $_REQUEST[cumedida];
  $common_claveps = $_REQUEST[common_claveps];

  if($suc==1){
    $tablasuc='procestex';
    $logeqp='logesteqp';
  }elseif($suc==2){
    $tablasuc='procestep';
    $logeqp='logesteqp2';
  }elseif($suc==3){
    $tablasuc='proceshf';
    $logeqp='logesteqp3';
  }elseif($suc==4){
    $tablasuc='procesrys';
    $logeqp='logesteqp4';
  }

  $Tabla  = "est";

  $Titulo = "Detalle de estudios [$busca]";

  $Niv    = $_COOKIE['LEVEL'];

  $OrdenDef = "id";            //Orden de la tabla por default

  $tamPag   = 13;

  if($ele=='1'){

    $Tablapdf    = "elepdf";
    $logpdf      = "logelepdf";
    $alterno      = '0';
    
  }elseif($ele=='2'){

    $Tablapdf    = "elealtpdf";
    $logpdf      = "logelealtpdf";
    $alterno      = '1';

  }elseif($ele=='3'){

    $Tablapdf    = "elealtpdf2";
    $logpdf      = "logelealtpdf2";
    $alterno      = '2';

  }

  $cSqlpdf     = "SELECT * FROM $Tablapdf WHERE estudio = '$busca'";

  
  $Edit     = array("Edit","Secuencia","Descripcion","Tipo","Longitud","No.Decimales","Min","Max","Elim",
              "-","Nele.ido","Cele.descripcion","Cele.tipo","Cele.longitud","Cele.decimales","Cele.min","Cele.max");

  if($op=='Agrega'){

    $lUp=mysql_query("INSERT into ests (estudio,descripcion) VALUES ('$busca','$_REQUEST[Sinonimo]')",$link);

    $lUp3  = mysql_query("INSERT INTO logestbas (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Agrego Sinonimo $_REQUEST[Sinonimo]')");

  }elseif($op=='Agrega_proceso'){

    $lUp=mysql_query("INSERT into proc_a_realizar (estudio,proceso) VALUES ('$busca','$_REQUEST[Proceso]')",$link);

    $lUp3  = mysql_query("INSERT INTO logestmue (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Agrego Proceso $_REQUEST[Proceso]')");

    $lUp = mysql_query("UPDATE $Tabla SET modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");

  }elseif($op=='El'){

     $lUp    = mysql_query("delete FROM ests WHERE estudio='$busca' and descripcion='$_REQUEST[Sinonimo]'",$link);

     $lUp3  = mysql_query("INSERT INTO logestbas (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Elimina Sinonimo $_REQUEST[Sinonimo]')");

  }elseif($op=='Elim'){

     $lUp    = mysql_query("delete FROM estd WHERE estudio='$busca' and producto='$_REQUEST[producto]' and suc='$_REQUEST[suc]'",$link);

    $lUp3  = mysql_query("INSERT INTO $logeqp (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Elimina Producto $_REQUEST[producto]')");

    $lUp = mysql_query("UPDATE $Tabla SET modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");

  }elseif($op=='Elimproceso'){

     $lUp    = mysql_query("delete FROM proc_a_realizar WHERE id='$_REQUEST[id]'",$link);

    $lUp3  = mysql_query("INSERT INTO logestmue (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Elimina Proceso $_REQUEST[proceso]')");

    $lUp = mysql_query("UPDATE $Tabla SET modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");

  }elseif($op=='Eliest'){

     $ldes    = mysql_fetch_array(mysql_query("select * FROM conest WHERE estudio='$busca' and id='$_REQUEST[id]'",$link));

    $lUp3  = mysql_query("INSERT INTO logestcon (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Elimina Estudio $ldes[descripcion]')");

     $lUp    = mysql_query("delete FROM conest WHERE estudio='$busca' and id='$_REQUEST[id]'",$link);

  }elseif($op=='Actualiza'){

          $cSql2="SELECT * FROM $tablasuc WHERE (estudio= '$busca')";

          $UpA=mysql_query($cSql2,$link);

            if(!$res2=mysql_fetch_array($UpA)){
              
              $lUp2=mysql_query("INSERT into $tablasuc (estudio,equipo,tecnica,maquila1,maquila2,maquila3,mixtolcd,mixtotpx,mixtohf,mixtorys,mixtomaq,estructura,matyeq,preparacion,posicion,tecnicaeq,postadq) VALUES ('$busca','$_REQUEST[Equipo]','$_REQUEST[Tecnica]','$_REQUEST[Maquila1]','$_REQUEST[Maquila2]','$_REQUEST[Maquila3]','$_REQUEST[Mixtolcd]','$_REQUEST[Mixtotpx]','$_REQUEST[Mixtohf]','$_REQUEST[Mixtorys]','$_REQUEST[Mixtomaq]','$_REQUEST[Estructura]','$_REQUEST[Matyeq]','$_REQUEST[Preparacion]','$_REQUEST[Posicion]','$_REQUEST[Tecnicaeq]','$_REQUEST[Postadq]')",$link);

              $lUp3  = mysql_query("INSERT INTO $logeqp (fecha,usr,estudio,concepto) VALUES
              ('$Fecha','$Usr','$_REQUEST[busca]','Registra datos en Suc $tablasuc')");

           }else{

              $lUp2 = mysql_query("UPDATE $tablasuc SET estudio='$busca',equipo='$_REQUEST[Equipo]',tecnica='$_REQUEST[Tecnica]',maquila1='$_REQUEST[Maquila1]',maquila2='$_REQUEST[Maquila2]',maquila3='$_REQUEST[Maquila3]',mixtolcd='$_REQUEST[Mixtolcd]',mixtotpx='$_REQUEST[Mixtotpx]',mixtohf='$_REQUEST[Mixtohf]',mixtorys='$_REQUEST[Mixtorys]',mixtomaq='$_REQUEST[Mixtomaq]',estructura='$_REQUEST[Estructura]',matyeq='$_REQUEST[Matyeq]',preparacion='$_REQUEST[Preparacion]',posicion='$_REQUEST[Posicion]',tecnicaeq='$_REQUEST[Tecnicaeq]',postadq='$_REQUEST[Postadq]' WHERE estudio='$busca' limit 1",$link);

              $lUp3  = mysql_query("INSERT INTO $logeqp (fecha,usr,estudio,concepto) VALUES
              ('$Fecha','$Usr','$_REQUEST[busca]','Modifica datos en Suc $tablasuc')");
           }

           $lUp = mysql_query("UPDATE $Tabla SET modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");

  }elseif($op=='Guardar'){

        $DepA=mysql_query("SELECT departamento FROM depd WHERE subdepto='$_REQUEST[Subdepto]'");

        $Dep=mysql_fetch_array($DepA);

        if($busca=='NUEVO'){

          $lUp=mysql_query("SELECT estudio FROM  $Tabla WHERE estudio='$_REQUEST[Estudio]' limit 1");

          $Exi=mysql_fetch_array($lUp);

          if($Exi[estudio]==$_REQUEST[Estudio]){

            $Msj="La Clave del estudio($_REQUEST[Estudio]) ya existe! favor de verificar...";

            header("Location: estudiose2.php?Msj=$Msj&pagina=$pagina");

          }else{

              $lUp = mysql_query("INSERT into $Tabla (estudio,descripcion,subdepto,depto,clavealt,agrego,fechalta,base,consentimiento,costo)
              VALUES
              ('$_REQUEST[Estudio]','$_REQUEST[Descripcion]','$_REQUEST[Subdepto]','$Dep[0]','$_REQUEST[Clavealt]','$Usr','$fechmod','$_REQUEST[Base]','$_REQUEST[Consentimiento]','$_REQUEST[Costo]')");

              Btc($Titulo,$_REQUEST[Estudio]);

              $lUp3  = mysql_query("INSERT INTO logestbas (fecha,usr,estudio,concepto) VALUES
              ('$Fecha','$Usr','$_REQUEST[busca]','Da de alta Estudio')");

              header("Location: estudiose2.php?busca=$_REQUEST[Estudio]&pagina=$pagina");
          }

        }else{

          $lUp = mysql_query("UPDATE $Tabla SET descripcion='$_REQUEST[Descripcion]',clavealt='$_REQUEST[Clavealt]',subdepto='$_REQUEST[Subdepto]',depto='$Dep[0]',activo='$_REQUEST[Activo]',modify='$Usr',fechmod='$fechmod',base='$_REQUEST[Base]',consentimiento='$_REQUEST[Consentimiento]',costo='$_REQUEST[Costo]' WHERE estudio='$busca' limit 1");

          $lUp3  = mysql_query("INSERT INTO logestbas (fecha,usr,estudio,concepto) VALUES
          ('$Fecha','$Usr','$_REQUEST[busca]','Modifica Datos basicos')");

        }

  }elseif($op=='Registrar'){

    if($Producto <> '' AND $_REQUEST[Cantidad] > 0 ){
                 
      $Up       = mysql_query("INSERT INTO estd (estudio,producto,idproducto,cantidad,suc) 
                 VALUES 
                 ('$busca','$Producto','$idproducto','$_REQUEST[Cantidad]','$_REQUEST[suc]')");

      $lUp3  = mysql_query("INSERT INTO $logeqp (fecha,usr,estudio,concepto) VALUES
      ('$Fecha','$Usr','$_REQUEST[busca]','Registra producto $Producto')");

      $lUp = mysql_query("UPDATE $Tabla SET modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");
         
     $Producto  = "";

   }
  }elseif($op=='Acepta'){

          $lUp = mysql_query("UPDATE $Tabla SET objetivo='$_REQUEST[Objetivo]',condiciones='$_REQUEST[Condiciones]',contenido='$_REQUEST[Contenido]',observaciones='$_REQUEST[Observaciones]',respradiologia='$_REQUEST[Respradiologia]',modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");

          $lUp3  = mysql_query("INSERT INTO logestdes (fecha,usr,estudio,concepto) VALUES
          ('$Fecha','$Usr','$_REQUEST[busca]','Modifica Datos generales')");

  }elseif($op=='GuardaPrec'){

    $lUp = mysql_query("UPDATE $Tabla SET lt1=$_REQUEST[Lt1],lt2='$_REQUEST[Lt2]',lt3='$_REQUEST[Lt3]',lt4='$_REQUEST[Lt4]',
     lt5='$_REQUEST[Lt5]',lt6='$_REQUEST[Lt6]',lt7='$_REQUEST[Lt7]',lt8='$_REQUEST[Lt8]',lt9='$_REQUEST[Lt9]',
     lt10='$_REQUEST[Lt10]',lt11='$_REQUEST[Lt11]',lt12='$_REQUEST[Lt12]',lt13='$_REQUEST[Lt13]',lt14='$_REQUEST[Lt14]',
     lt15='$_REQUEST[Lt15]',lt16='$_REQUEST[Lt16]',lt17='$_REQUEST[Lt17]',lt18='$_REQUEST[Lt18]',lt19='$_REQUEST[Lt19]'
     ,lt20='$_REQUEST[Lt20]',lt21='$_REQUEST[Lt21]',lt22='$_REQUEST[Lt22]',lt23='$_REQUEST[Lt23]',modify='$Usr',fechmod='$fechmod'
     WHERE estudio='$busca' limit 1");

      $lUp3  = mysql_query("INSERT INTO logestadm (fecha,usr,estudio,concepto) VALUES
      ('$Fecha','$Usr','$_REQUEST[busca]','Modifica Precios')");

  }elseif($op == "Actualiza/clasificacion") {

    $cSql  = "UPDATE est SET inv_cunidad='$_REQUEST[cumedida]',inv_cproducto='$_REQUEST[common_claveps]' WHERE estudio='$busca' limit 1";

    $lUp3  = mysql_query("INSERT INTO logestadm (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Modifica Datos de clasificacion')");

    $lUp = mysql_query("UPDATE $Tabla SET modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");

    if (!mysql_query($cSql)) {
        echo "<div align='center'>$cSql</div>";
        $Archivo = 'INV';
        die('<div align="center"><p>&nbsp;</p>Error critico[paso 1]<br>el proceso <b>NO</b> se finaliz&oacute; correctamente, favor de informar al <b>departamento de sistemas</b><br><b> ' . $Archivo . ' ' . mysql_error() . '</b><br> favor de dar click en la flecha <a href=menu.php?op=102><img src=lib/regresa.jpg border=0></a> para regresar</div>');
    }

  }elseif($op=='GuardaPromo'){

          $lUp = mysql_query("UPDATE $Tabla SET ventajas='$_REQUEST[Ventajas]',promogral='$_REQUEST[Promogral]',msjadmvo='$_REQUEST[Msjadmvo]',modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");

          $lUp3  = mysql_query("INSERT INTO logestatn (fecha,usr,estudio,concepto) VALUES
          ('$Fecha','$Usr','$_REQUEST[busca]','Modifica Datos de promocion')");

  }elseif($op=='Guarda'){

          $lUp = mysql_query("UPDATE $Tabla SET proceso='$_REQUEST[Proceso]',tiempoestd='$_REQUEST[Tiempoestd]',tiempoesth='$_REQUEST[Tiempoesth]',entord='$_REQUEST[Entord]',entordh='$_REQUEST[Entordh]',enthosd='$_REQUEST[Enthosd]',enthos='$_REQUEST[Enthos]',enturgd='$_REQUEST[Enturgd]',enturg='$_REQUEST[Enturg]',modify='$Usr',fechmod='$fechmod',dobleinterpreta='$_REQUEST[Dobleinterpreta]',producto_entregar='$_REQUEST[Producto_entregar]' WHERE estudio='$busca' limit 1");

            $lUp3  = mysql_query("INSERT INTO logestmue (fecha,usr,estudio,concepto) VALUES
            ('$Fecha','$Usr','$_REQUEST[busca]','Modifica datos de muestras')");

  }elseif($op=='Reg_Estudio'){
                
      $Up       = mysql_query("INSERT INTO conest (estudio,conest,descripcion) 
                 VALUES 
                 ('$busca','$_REQUEST[Estudio]','$_REQUEST[descripcion]')");

      $lUp3  = mysql_query("INSERT INTO logestcon (fecha,usr,estudio,concepto) VALUES
      ('$Fecha','$Usr','$_REQUEST[busca]','Registra producto $_REQUEST[descripcion]')");

      $lUp = mysql_query("UPDATE $Tabla SET modify='$Usr',fechmod='$fechmod' WHERE estudio='$busca' limit 1");


  }elseif($_REQUEST[Boton] == Agregar){

    $lUp   = mysql_query("INSERT INTO $Tablapdf (estudio,descripcion,tipo,unidad,longitud,decimales,id,min,max,nota,vlogico,vtexto,alineacion,celda1,celda2,celda3,celdas,valref,idvalor1,parentesis1,valor1,operador1,idvalor2,valor2,parentesis2,operador2,idvalor3,valor3,parentesis3,operador3,idvalor4,valor4,parentesis4,calculo,condiciona) 
          VALUES 
          ('$busca','$_REQUEST[Descripcion]','$_REQUEST[Tipo]','$_REQUEST[Unidad]','$_REQUEST[Longitud]','$_REQUEST[Decimales]','$_REQUEST[Id]','$_REQUEST[Min]','$_REQUEST[Max]','$_REQUEST[Nota]','$_REQUEST[Vlogico]','$_REQUEST[Vtexto]','$_REQUEST[Alineacion]','$_REQUEST[Celda1]','$_REQUEST[Celda2]','$_REQUEST[Celda3]','$_REQUEST[Celdas]','$_REQUEST[Valref]','$_REQUEST[Idvalor1]','$_REQUEST[Parentesis1]','$_REQUEST[Valor1]','$_REQUEST[Operador1]','$_REQUEST[Idvalor2]','$_REQUEST[Valor2]','$_REQUEST[Parentesis2]','$_REQUEST[Operador2]','$_REQUEST[Idvalor3]','$_REQUEST[Valor3]','$_REQUEST[Parentesis3]','$_REQUEST[Operador3]','$_REQUEST[Idvalor4]','$_REQUEST[Valor4]','$_REQUEST[Parentesis4]','$_REQUEST[Calculo]','$_REQUEST[Condiciona]')");

      $lUp2   = mysql_query("UPDATE $Tablapdf SET fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

      $lUp3  = mysql_query("INSERT INTO $logpdf (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Agrego elemento $_REQUEST[Id]')");

  }elseif($_REQUEST[Boton] == Actualizar){
    
      $lUp   = mysql_query("UPDATE $Tablapdf SET descripcion='$_REQUEST[Descripcion]',tipo='$_REQUEST[Tipo]',unidad='$_REQUEST[Unidad]',
               longitud='$_REQUEST[Longitud]',decimales='$_REQUEST[Decimales]',id='$_REQUEST[Id]',
               min='$_REQUEST[Min]',max='$_REQUEST[Max]',nota='$_REQUEST[Nota]',vlogico='$_REQUEST[Vlogico]',vtexto='$_REQUEST[Vtexto]',alineacion='$_REQUEST[Alineacion]',celda1='$_REQUEST[Celda1]',celda2='$_REQUEST[Celda2]',celda3='$_REQUEST[Celda3]',celdas='$_REQUEST[Celdas]',valref='$_REQUEST[Valref]',idvalor1='$_REQUEST[Idvalor1]',parentesis1='$_REQUEST[Parentesis1]',valor1='$_REQUEST[Valor1]',operador1='$_REQUEST[Operador1]',idvalor2='$_REQUEST[Idvalor2]',valor2='$_REQUEST[Valor2]',parentesis2='$_REQUEST[Parentesis2]',operador2='$_REQUEST[Operador2]',idvalor3='$_REQUEST[Idvalor3]',valor3='$_REQUEST[Valor3]',parentesis3='$_REQUEST[Parentesis3]',operador3='$_REQUEST[Operador3]',idvalor4='$_REQUEST[Idvalor4]',valor4='$_REQUEST[Valor4]',parentesis4='$_REQUEST[Parentesis4]',calculo='$_REQUEST[Calculo]',condiciona='$_REQUEST[Condiciona]'
               WHERE idnvo='$_REQUEST[Up]'");

      $lUp2   = mysql_query("UPDATE $Tablapdf SET fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

      $lUp3  = mysql_query("INSERT INTO $logpdf (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Actualizo elemento $_REQUEST[Id]')");


  }elseif($op=='Si'){                    //Elimina Registro

     $lUp   = mysql_query("DELETE FROM $Tablapdf WHERE estudio='$busca' AND id='$_REQUEST[cId]' LIMIT 1");
     $Msj   = "El registro: $_REQUEST[cId] a sido dado de baja";

      $lUp3  = mysql_query("INSERT INTO $logpdf (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$busca','Elimina elemento $_REQUEST[cId]')");

  }elseif($op=='cerrar'){

          $lUp   = mysql_query("UPDATE $Tablapdf SET bloqueado='Si', fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO $logpdf (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Estudio')");

  }elseif($op=='abrir'){

          $lUp   = mysql_query("UPDATE $Tablapdf SET bloqueado='No', fecha='$Fecha', usr='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO $logpdf (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Estudio')");

  }elseif($op=='cerrarbas'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqbas='Si', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestbas (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Secc Basicos')");

  }elseif($op=='abrirbas'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqbas='No', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestbas (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Secc Basicos')");

  }elseif($op=='cerrareqp'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqeqp='Si', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logesteqp (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Secc Equipos')");

  }elseif($op=='abrireqp'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqeqp='No', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logesteqp (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Secc Equipos')");

  }elseif($op=='cerrarmue'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqmue='Si', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestmue (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Secc Muestras')");

  }elseif($op=='abrirmue'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqmue='No', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestmue (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Secc Muestras')");

  }elseif($op=='cerrarcon'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqcon='Si', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestcon (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Secc Contenido')");

  }elseif($op=='abrircon'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqcon='No', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestcon (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Secc Contenido')");

  }elseif($op=='cerrardes'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqdes='Si', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestdes (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Secc General')");

  }elseif($op=='abrirdes'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqdes='No', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestdes (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Secc General')");

  }elseif($op=='cerraradm'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqadm='Si', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestadm (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Secc Administracion')");

  }elseif($op=='abriradm'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqadm='No', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestadm (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Secc Administracion')");

  }elseif($op=='cerraratn'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqatn='Si', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestatn (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Cierra Secc Promocion')");

  }elseif($op=='abriratn'){

          $lUp   = mysql_query("UPDATE $Tabla SET bloqatn='No', fechmod='$Fecha', modify='$Usr' WHERE estudio='$_REQUEST[busca]'");

          $lUp3  = mysql_query("INSERT INTO logestatn (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Abre Secc Promocion')");

  }elseif($op=='= LCD-TX'){

        $cSql3="SELECT estd.estudio,estd.producto,estd.idproducto,estd.cantidad,invl.descripcion,invl.claveFROM estd,invl WHERE estd.estudio='$busca' and estd.idproducto=invl.id and estd.suc='1'";
        $result3=mysql_query($cSql3);
        while ($row2=mysql_fetch_array($result3)){

                $Up       = mysql_query("INSERT INTO estd (estudio,producto,idproducto,cantidad,suc) 
                 VALUES 
                 ('$busca','$row2[producto]','$row2[idproducto]','$row2[cantidad]','$suc')");
        }


    $lUp3  = mysql_query("INSERT INTO $logeqp (fecha,usr,estudio,concepto) VALUES
    ('$Fecha','$Usr','$_REQUEST[busca]','Agrega Productos de Suc Texcoco')");

  }elseif($op=='COD'){

        $cSql4="SELECT estd.estudio,estd.producto,invl.clave,invl.id FROM estd,invl WHERE estd.estudio='$busca' and estd.producto=invl.clave and estd.suc='$suc'";
        $result4=mysql_query($cSql4);

        while ($row4=mysql_fetch_array($result4)){

                $Up       = mysql_query("UPDATE estd set idproducto='$row4[id]' WHERE estudio='$_REQUEST[busca]' and estd.producto='$row4[clave]'");
        }
  }

  if($_REQUEST[Boton] == Salir){
    header("Location: estudios.php?pagina=$pagina");
  }

  $cSql = "SELECT estudio,descripcion,objetivo,condiciones,tubocantidad,tiempoest,entord,entexp,enthos,enturg,
    equipo,muestras,estpropio,subdepto,contenido,comision,observaciones,proceso,clavealt,respradiologia,activo,dobleinterpreta,modify,fechmod,agrego,fechalta,depto,base,ventajas,promogral,tiempoestd,tiempoesth,entordh,enthosd,enturgd,bloqbas,bloqeqp,bloqmue,bloqcon,bloqdes,bloqadm,bloqatn,msjadmvo,consentimiento,producto_entregar,costo
          FROM $Tabla WHERE (estudio= '$busca')";

  $CpoA  = mysql_query($cSql);
  $Cpo   = mysql_fetch_array($CpoA);
  $lAg   = $Descripcion<>$Cpo[Descripcion];
  $Fecha = date("Y-m-d");

 require ("config.php");

?>
<html>
<head>
<title><?php echo $Titulo;?></title>
<meta http-equiv="Pragma" content="no-cache" />
<style type="text/css">

#button {
padding: 10;
}

#button li {
display: inline;
}

#button li a {
font-family: Arial;
font-size:13px;
text-decoration: none;
float:center;
padding: 10px;
background-color: #2175bc;
color: #fff;

}

#button li a:hover {
box-shadow: 0 0.5rem 0.5rem rgba(5, 0, 0, 3);
background-color: #145a32;
margin-top:-2px;
padding-bottom:12px;
text-decoration:underline;
/*box-shadow:0px 4px 0px #c0392b;*/
} 

#button li a:active {
background-color: #000000;
margin-top:-2px;
padding-bottom:12px;
}

#button li a.current {
box-shadow: 0 0.5rem 0.5rem rgba(5, 0, 0, 3);
background-color: #145a32;
margin-top:-2px;
padding-bottom:12px;
text-decoration:underline;
/*box-shadow:0px 4px 0px #c0392b;*/
}

</style>
</head>
<body bgcolor="#FFFFFF" onLoad="cFocus()">

<?php headymenu($Titulo,0); ?>

<script language="JavaScript1.2">

function AbreVentana(url){
   window.open(url,"upword","width=300,height=400,left=600,top=150,scrollbars=no,location=no,dependent=yes,resizable=no");
}

function Vt(url){
   window.open(url,"conformacion","scrollbars=yes,status=no,tollbar=no,menubar=no,resizable=yes,width=750,height=500,left=20,top=20")
}

function cFocus(){
  document.form1.Estudio.focus();
}

function Subephp(url){
   window.open(url,"upphp","width=300,height=400,left=600,top=150,scrollbars=no,location=no,dependent=yes,resizable=no");
}


function Completo(){
var lRt;
lRt=true;
if(document.form2.Apellidom.value==""){lRt=false;}
if(document.form2.Descripcion.value==""){lRt=false;}
if(document.form2.Nombre.value==""){lRt=false;}
if(!lRt){
    alert("Faltan datos por llenar, favor de verificar");
    return false;
}
document.form1.Apellidom.value=document.form2.Apellidom.value
document.form1.Descripcion.value=document.form2.Descripcion.value
document.form1.Nombre.value=document.form2.Nombre.value
return true;
}

function Mayusculas(cCampo){
if (cCampo=='Estudio'){document.form1.Estudio.value=document.form1.Estudio.value.toUpperCase();
}if (cCampo=='Descripcion'){document.form1.Descripcion.value=document.form1.Descripcion.value.toUpperCase();
}if (cCampo=='Tubocantidad'){document.form1.Tubocantidad.value=document.form1.Tubocantidad.value.toUpperCase();
}if (cCampo=='Equipo'){document.form1.Equipo.value=document.form1.Equipo.value.toUpperCase();
}if (cCampo=='Clavealt'){document.form1.Clavealt.value=document.form1.Clavealt.value.toUpperCase();
}if (cCampo=='Sinonimo'){document.form2.Sinonimo.value=document.form2.Sinonimo.value.toUpperCase();}
}
</script>

<table width='80%' border='0' align='center'>

<?php if($busca=='NUEVO'){?>

<tr>
<td align="center">
<ul id='button'>
<li><?php echo "<a href=estudiose2.php?opm=basicos&busca=$_REQUEST[busca]>Datos Basicos</a>"; ?></li>  
</ul>
</td>
</tr>
</table>

<?php }else{?>

<tr>
<td align="center">
<ul id='button'>

<?php if($opm=='basicos'){?>
  <li><?php echo "<a href=estudiose2.php?opm=basicos&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Datos Basicos</a>"; ?></li>
<?php }else{?>
  <li><?php echo "<a href=estudiose2.php?opm=basicos&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Datos Basicos</a>"; ?></li>
<?php }?>

<?php if($opm=='equipos'){?>
  <li><?php echo "<a href=estudiose2.php?opm=equipos&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Equipos por Unidad</a>"; ?></li>
<?php }else{?>
  <li><?php echo "<a href=estudiose2.php?opm=equipos&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Equipos por Unidad</a>"; ?></li>
<?php }?>

<?php if($opm=='muestras'){?>
    <li><?php echo "<a href=estudiose2.php?opm=muestras&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Muestras</a>"; ?></li>
<?php }else{?>
    <li><?php echo "<a href=estudiose2.php?opm=muestras&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Muestras</a>"; ?></li>
<?php }?>

<?php if($opm=='contenido'){?>
  <li><?php echo "<a href=estudiose2.php?opm=contenido&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Contenido</a>"; ?></li>
<?php }else{?>
  <li><?php echo "<a href=estudiose2.php?opm=contenido&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Contenido</a>"; ?></li>
<?php }?>

<?php if($opm=='general'){?>
  <li><?php echo "<a href=estudiose2.php?opm=general&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Descripcion general</a>"; ?></li>
<?php }else{?>
  <li><?php echo "<a href=estudiose2.php?opm=general&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Descripcion general</a>"; ?></li>
<?php }?>

<?php if($opm=='admin'){?>
  <li><?php echo "<a href=estudiose2.php?opm=admin&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Administracion</a>"; ?></li>
<?php }else{?>
  <li><?php echo "<a href=estudiose2.php?opm=admin&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Administracion</a>"; ?></li>
<?php }?>

<?php if($opm=='promocion'){?>
  <li><?php echo "<a href=estudiose2.php?opm=promocion&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Atn clientes/Promocion</a>"; ?></li>   
<?php }else{?>
  <li><?php echo "<a href=estudiose2.php?opm=promocion&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Atn clientes/Promocion</a>"; ?></li>   
<?php }?>

<?php if($opm=='elementos'){?>
  <li><?php echo "<a href=estudiose2.php?opm=elementos&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina] class=current>Elementos de Cap./Imp.</a>"; ?></li>   
<?php }else{?>
  <li><?php echo "<a href=estudiose2.php?opm=elementos&busca=$_REQUEST[busca]&pagina=$_REQUEST[pagina]>Elementos de Cap./Imp.</a>"; ?></li>   
<?php }?>

</ul>
</td>
</tr>
</table>

<?php }?>

<?php

if($opm=='basicos'){

      echo "<table width='100%' border='0'><tr>";

      echo "<td> $Gfont ";

      echo "<form name='form1' method='get' action='estudiose2.php'>";

      echo "<table width='70%' border='0' align='center'>";

      if($busca=='NUEVO' and $op='Depto'){

            echo "<tr><td align='right'>$Gfont Estudio $Gfon</td><td align='left'><input type='text' name='Estudio' value='$_REQUEST[Estudio]' maxlength='7' onBlur=Mayusculas('Estudio') size='6'></td>";

            echo "<td align='right'>$Gfont Clave alterna </td><td align='left'><input type='text' name='Clavealt' value='$_REQUEST[Clavealt]' maxlength='20' onBlur=Mayusculas('Clavealt') size='20'></td>";

            echo "<td align='right'>$Gfont Costo </td><td align='left'><input type='text' name='Costo'  size='5' value='$_REQUEST[Costo]'></td></tr>";

            echo "<tr><td align='right'>$Gfont Descripcion $Gfon</td><td align='left' colspan='3'><input type='text' name='Descripcion' value='$_REQUEST[Descripcion]' maxlength='110' onBlur=Mayusculas('Descripcion') size='110'></td></tr>";   

            echo "<tr><td align='right'>$Gfont Departamento $Gfon</td><td align='left'>";

            $DepA=mysql_query("SELECT * FROM dep WHERE dep.departamento<>8");

            echo "<SELECT name='Depto'>";
            while ($dep=mysql_fetch_array($DepA)){
                   echo "<option value='$dep[0]'>$dep[0]: $dep[1]</option>";
            }

            if(!$Depto){
                $DepB=mysql_query("SELECT * FROM dep WHERE dep.departamento=$Cpo[depto]");    
            }else{ 
                $DepB=mysql_query("SELECT * FROM dep WHERE dep.departamento=$Depto");    
            }

            $DepB2=mysql_fetch_array($DepB);  
            echo "<option SELECTed value='$DepB2[departamento]'>$DepB2[nombre]</option>";

            echo "</SELECT>";

            echo "<input type='submit' name='op' value='Depto'></td>";

            echo "<td align='right'>$Gfont Sub-departamento $Gfon</td><td align='left'>";

            $cSub=mysql_query("SELECT * FROM depd WHERE depd.departamento='$Depto'");
            
            echo "<SELECT name='Subdepto'>";
            while ($depd=mysql_fetch_array($cSub)){
                   echo "<option value='$depd[subdepto]'>$depd[id]: $depd[subdepto]</option>";
            }
            echo "<option SELECTed value='$Cpo[13]'>$Cpo[13]</option>";
            echo "</SELECT>";

            echo "</td></tr>";

            echo "<tr><td align='right'>$Gfont Sinonimos $Gfon</td>";
            echo "<td align='left'>";
            echo "</td></tr>";

            echo "<tr><td align='right'>$Gfont Status &nbsp;</td><td>";

            echo "<select name='Activo'>";
            echo "<option value='Si'>Activo</option>";
            echo "<option value='No'>Inactivo</option>";
            if($Cpo[activo]=='Si'){ $statusa='Activo'; }else{ $statusa='Inactivo'; }
            echo "<option selected value='$Cpo[activo]'>$statusa</option>";
            echo "</select>";
            echo "</td></tr>";

            echo "<tr><td align='right'>$Gfont Tipo de Estudio &nbsp;</td><td>";
            echo "<select name='Base'>";
            echo "<option value='Individual'>Individual</option>";
            echo "<option value='Asociado'>Asociado</option>";
            echo "<option value='Agrupado'>Agrupado</option>";
            echo "<option value='Mixto'>Mixto</option>";
            echo "<option value='Combinado'>Combinado</option>";
            echo "<option selected value='$Cpo[base]'>$Cpo[base]</option>";
            echo "</select>";
            echo "</td></tr>";

            echo "<tr><td align='right'>$Gfont Consentimiento Inf. &nbsp;</td><td>";

            echo "<select name='Consentimiento'>";
            echo "<option value='Si'>Si</option>";
            echo "<option value='No'>No</option>";
            echo "<option selected value='$Cpo[consentimiento]'>$Cpo[consentimiento]</option>";
            echo "</select>";
            echo "</td></tr>";

      }else{

          echo "<tr><td align='right'>$Gfont Estudio $Gfon</td><td align='left'><input type='text' name='Estudio' value='$Cpo[0]' maxlength='7' onBlur=Mayusculas('Estudio') size='6'></td>";

            echo "<td align='right'>$Gfont Clave alterna </td><td align='left'><input type='text' name='Clavealt' value='$Cpo[clavealt]' maxlength='20' onBlur=Mayusculas('Clavealt') size='20'></td>";

            echo "<td align='right'>$Gfont Costo </td><td align='left'><input type='text' name='Costo' value='$Cpo[costo]' size='5'></td></tr>";

          echo "<tr><td align='right'>$Gfont Descripcion $Gfon</td><td align='left' colspan='3'><input type='text' name='Descripcion' value='$Cpo[descripcion]' maxlength='110' onBlur=Mayusculas('Descripcion') size='110'></td></tr>";   

          echo "<tr><td align='right'>$Gfont Departamento $Gfon</td><td align='left'>";

          $DepA=mysql_query("SELECT * FROM dep WHERE dep.departamento<>8");

          echo "<SELECT name='Depto'>";
          while ($dep=mysql_fetch_array($DepA)){
                 echo "<option value='$dep[0]'>$dep[0]: $dep[1]</option>";
          }

          if(!$Depto){
              $DepB=mysql_query("SELECT * FROM dep WHERE dep.departamento=$Cpo[depto]");    
          }else{ 
              $DepB=mysql_query("SELECT * FROM dep WHERE dep.departamento=$Depto");    
          }

          $DepB2=mysql_fetch_array($DepB);  
          echo "<option SELECTed value='$DepB2[departamento]'>$DepB2[nombre]</option>";

          echo "</SELECT>";

          echo "<input type='submit' name='op' value='Depto'></td>";

          echo "<td align='right'>$Gfont Sub-departamento $Gfon</td><td align='left'>";

          $cSub=mysql_query("SELECT * FROM depd WHERE depd.departamento='$Depto'");
          
          echo "<SELECT name='Subdepto'>";
          while ($depd=mysql_fetch_array($cSub)){
                 echo "<option value='$depd[subdepto]'>$depd[id]: $depd[subdepto]</option>";
          }
          echo "<option SELECTed value='$Cpo[13]'>$Cpo[13]</option>";
          echo "</SELECT>";

          echo "</td></tr>";

          echo "<tr><td align='right'>$Gfont Sinonimos $Gfon</td>";
          echo "<td align='left'><input name='Sinonimo' type='text' id='Sinonimo' onBlur=Mayusculas(Sinonimo) size='40'><input type='submit' name='op' value='Agrega'>";
              $cSql="SELECT descripcion FROM ests WHERE estudio='$busca'";
              $result=mysql_query($cSql);
              while ($row=mysql_fetch_array($result)){
                    if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

                    echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

                     echo "<td bgcolor='#FFFFFF'></td><td align='left'> $Gfont $row[0] &nbsp; &nbsp; </td>";

                    if($Cpo[bloqbas]=='No'){

                         echo "<td align='center'><a href='estudiose2.php?busca=$busca&Sinonimo=$row[0]&pagina=$pagina&op=El&opm=basicos'><img src='lib/deleon.png' alt='Edita reg' border='0'></a></td></tr>";

                       }else{

                         echo "<td align='center'> - </td></tr>";

                       }

                     $nRng++;
              }
              mysql_free_result($result);

          echo "</td></tr>";

          echo "<tr><td align='right'>$Gfont Status &nbsp;</td><td>";

          echo "<select name='Activo'>";
          echo "<option value='Si'>Activo</option>";
          echo "<option value='No'>Inactivo</option>";
          if($Cpo[activo]=='Si'){ $statusa='Activo'; }else{ $statusa='Inactivo'; }
          echo "<option selected value='$Cpo[activo]'>$statusa</option>";
          echo "</select>";
          echo "</td></tr>";

          echo "<tr><td align='right'>$Gfont Tipo de Estudio &nbsp;</td><td>";

          echo "<select name='Base'>";
          echo "<option value='Individual'>Individual</option>";
          echo "<option value='Asociado'>Asociado</option>";
          echo "<option value='Agrupado'>Agrupado</option>";
          echo "<option value='Mixto'>Mixto</option>";
          echo "<option value='Combinado'>Combinado</option>";
          echo "<option selected value='$Cpo[base]'>$Cpo[base]</option>";
          echo "</select>";
          echo "</td></tr>";

          echo "<tr><td align='right'>$Gfont Consentimiento Inf. &nbsp;</td><td>";

          echo "<select name='Consentimiento'>";
          echo "<option value='Si'>Si</option>";
          echo "<option value='No'>No</option>";
          echo "<option selected value='$Cpo[consentimiento]'>$Cpo[consentimiento]</option>";
          echo "</select>";
          echo "</td></tr>";

    }

      if($Cpo[bloqbas]=='No' or $busca=='NUEVO'){

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=basicos&op=cerrarbas&pagina=$pagina><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a> &nbsp;  &nbsp;  &nbsp; <input type='submit' name='op' value='Guardar'>&nbsp; &nbsp; ";

      }else{

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=basicos&op=abrirbas&pagina=$pagina><font size='2'> Abrir </font><img src='images/candadoc.png' alt='pdf' border='0'></a>";
      }

      echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'>";
      echo "<input type='hidden' name='opm' value='basicos'>";
      echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
      echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";

      echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logmodest.php?busca=$busca&opm=basicos')><font size='1'> *** Modificaciones ***</a>  </td></tr>";
      
      echo "</table>";

      echo "</form>";

}elseif($opm=='equipos'){

      echo "<form name='form1' method='get' action='estudiose2.php'>";
      $Pag1="informes/".$busca."-descripcion.php";  	//Material
      $Pag2="informes/".$busca."-pre.php";   //Condiciones
      $Pag3="informes/".$busca."-indicaciones.php";   //Manual
      $Pag4="informes/".$busca."-instructivo.php";   //Objetivo
	   $estud = strtolower($Cpo[estudio]);          

/***   Proceso y clasificacion   ***/

        $colorm='#EDF9FE';

      if($suc=='1'){

        $colorm1='#c4aef1';
        $colorm2='#EDF9FE';
        $colorm3='#EDF9FE';
        $colorm4='#EDF9FE';

      }elseif($suc=='2'){

        $colorm2='#c4aef1';
        $colorm1='#EDF9FE';
        $colorm3='#EDF9FE';
        $colorm4='#EDF9FE';

      }elseif($suc=='3'){

        $colorm3='#c4aef1';
        $colorm1='#EDF9FE';
        $colorm2='#EDF9FE';
        $colorm4='#EDF9FE';

      }elseif($suc=='4'){

        $colorm4='#c4aef1';
        $colorm2='#EDF9FE';
        $colorm3='#EDF9FE';
        $colorm1='#EDF9FE';

      }

      echo "<table width='80%' border='0' cellpadding='6' cellspacing='0' align='center'>";

      echo "<tr border='1'><td align='center' bgcolor=$colorm1><a href='estudiose2.php?suc=1&busca=$busca&opm=equipos&pagina=$_REQUEST[pagina]'>$Gfont LCD-TX &nbsp;</font></a></td>
    <td align='center' bgcolor=$colorm2><a href='estudiose2.php?suc=2&busca=$busca&opm=equipos&pagina=$_REQUEST[pagina]'>$Gfont LCD-TPX &nbsp;</font></a></td>
    <td align='center' bgcolor=$colorm3><a href='estudiose2.php?suc=3&busca=$busca&opm=equipos&pagina=$_REQUEST[pagina]'>$Gfont LCD-HF &nbsp;</font></a></td>
    <td align='center' bgcolor=$colorm4><a href='estudiose2.php?suc=4&busca=$busca&opm=equipos&pagina=$_REQUEST[pagina]'>$Gfont LCD-LR &nbsp;</font></a></td></tr>";

      echo "</table>";

      echo "<br>";

      echo "<table width='80%' border='0' align='center' cellpadding='3'>";

     echo "<tr><td align='right'>$Gfont Equipo: </td><td colspan='2'>";

      $csuc=mysql_query("SELECT * FROM $tablasuc WHERE estudio='$busca' limit 1");

      $proest=mysql_fetch_array($csuc);

      $ceqps=mysql_query("SELECT * FROM eqp");
      
      echo "<SELECT name='Equipo'>";
      echo "<option value=' '>*** Ninguno ***</option>";

      while ($ceqp=mysql_fetch_array($ceqps)){
             echo "<option value='$ceqp[2]'>$ceqp[0]: $ceqp[2]</option>";
      }
      echo "<option SELECTed value='$proest[2]'>$proest[2]</option>";
      echo "</SELECT>";

      $LgsA   = mysql_query("SELECT * FROM $logeqp WHERE estudio='$busca' order by fecha desc"); 

      $Lgs=mysql_fetch_array($LgsA);

      echo "</td><td align='left' colspan='2'><font size='2'> Ultima Modificacion por: $Lgs[usr] - $Lgs[fecha] </font></td></tr>";

     echo "<tr><td align='right'>$Gfont Tecnica: </td><td colspan='4'>";

      //$cSub=mysql_query("SELECT subdepto FROM depd");
      $ctec=mysql_query("SELECT * FROM tec");
      
      echo "<SELECT name='Tecnica'>";
      echo "<option value=' '>*** Ninguno ***</option>";
      while ($tec=mysql_fetch_array($ctec)){
             echo "<option value='$tec[1]'>$tec[0]: $tec[1]</option>";
      }
      echo "<option SELECTed value='$proest[3]'>$proest[3]</option>";
      echo "</SELECT>";

      echo "</td></tr>";

      echo "<tr><td align='right'>$Gfont Externo 1: </td><td colspan='4'>";

      $cmql=mysql_query("SELECT * FROM mql");
      
      echo "<SELECT name='Maquila1'>";
      echo "<option value=' '>*** Ninguno ***</option>";

      while ($mql=mysql_fetch_array($cmql)){
             echo "<option value='$mql[1]'>$mql[0]: $mql[1]</option>";
      }
      echo "<option SELECTed value='$proest[4]'>$proest[4]</option>";
      echo "</SELECT>";

      echo "</td></tr>";

      echo "<tr><td align='right'>$Gfont Externo 2: </td><td colspan='4'>";

      $cmql2=mysql_query("SELECT * FROM mql");
      
      echo "<SELECT name='Maquila2'>";
      echo "<option value=' '>*** Ninguno ***</option>";
      while ($mql2=mysql_fetch_array($cmql2)){
             echo "<option value='$mql2[1]'>$mql2[0]: $mql2[1]</option>";
      }
      echo "<option SELECTed value='$proest[5]'>$proest[5]</option>";
      echo "</SELECT>";

      echo "</td></tr>";

      echo "<tr><td align='right'>$Gfont Externo 3: </td><td colspan='4'>";

      $cmql3=mysql_query("SELECT * FROM mql");
      
      echo "<SELECT name='Maquila3'>";
      echo "<option value=' '>*** Ninguno ***</option>";
      while ($mql3=mysql_fetch_array($cmql3)){
             echo "<option value='$mql3[1]'>$mql3[0]: $mql3[1]</option>";
      }
      echo "<option SELECTed value='$proest[6]'>$proest[6]</option>";
      echo "</SELECT>";

      echo "</td></tr>";

      $Mixtolcdcheck='';
      $Mixtotpxcheck='';
      $Mixtohfcheck='';
      $Mixtoryscheck='';
      $Mixtomaqcheck='';

      if($proest[mixtolcd]=='1'){
        
        $Mixtolcdcheck='checked';

      }

      if($proest[mixtotpx]=='1'){

        $Mixtotpxcheck='checked';

      }

      if($proest[mixtohf]=='1'){

        $Mixtohfcheck='checked';

      }

      if($proest[mixtorys]=='1'){

        $Mixtoryscheck='checked';

      }

      if($proest[mixtomaq]=='1'){

        $Mixtomaqcheck='checked';

      }

      echo "<tr><td align='right'>$Gfont Mixto: </td>";
      echo "<td colspan='4'>$Gfont<input type='checkbox' value='1' name='Mixtolcd' $Mixtolcdcheck> LCD-TX </font>";
      echo "$Gfont<input type='checkbox' value='1' name='Mixtotpx' $Mixtotpxcheck> LCD-TPX </font>";
      echo "$Gfont<input type='checkbox' value='1' name='Mixtohf' $Mixtohfcheck> LCD-HF </font>";
      echo "$Gfont<input type='checkbox' value='1' name='Mixtorys' $Mixtoryscheck> LCD-LR </font>";
      echo "$Gfont<input type='checkbox' value='1' name='Mixtomaq' $Mixtomaqcheck> Externo </font></td></tr>";


      echo "<tr><td align='right'>$Gfont Producto $Gfon</td><td align='left' colspan='1'>";

      echo "<input class='Input' type='text' name='Producto' size='20' value='$Producto'> <a href='invlab6.php?orden=invl.descripcion&estudio=$busca&suc=$suc&pagina=$pagina'><img src='lib/lupa_o.gif' alt='Catalogo' border='0' width='20' height='20'></a></td>";

   //  if($row4s==$suc or $row4s=='0'){

        echo "<td align='left'>$Gfont Cant $Gfon <input class='Input' type='text' name='Cantidad' size='4' value=''><INPUT TYPE='SUBMIT' name='op' value='Registrar'></td>";

   //  }



      if($suc=='1'){
          echo "<td align='left' colspan='2'></td>";
      }else{

       // if($row4s==$suc or $row4s=='0'){

          echo "<td align='left' colspan='2'><INPUT TYPE='SUBMIT' name='op' value='= LCD-TX'></td>";

      // }
      }

    // if($row4s==$suc or $row4s=='0'){

          echo "<td align='left' colspan='2'><INPUT TYPE='SUBMIT' name='op' value='COD'></td></tr>";

    //  }

        $cSql2="SELECT estd.estudio,estd.producto,estd.idproducto,estd.cantidad,invl.descripcion,invl.clave FROM estd,invl WHERE estd.estudio='$busca' and estd.producto=invl.clave and estd.suc='$suc'";
        $result2=mysql_query($cSql2);
        while ($row2=mysql_fetch_array($result2)){
        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
      
            echo "<td bgcolor='#FFFFFF'></td><td align='left'> $Gfont $row2[idproducto] &nbsp; &nbsp; </td><td align='left'> $Gfont $row2[producto] &nbsp; &nbsp; </td><td align='left'>$Gfont $row2[descripcion]</td><td align='center'>$Gfont $row2[cantidad]</td>";
          
          if($Cpo[bloqeqp]=='No'){
  //            if($row4s==$suc or $row4s=='0'){

                  echo "<td align='center'><a href='estudiose2.php?busca=$busca&producto=$row2[producto]&op=Elim&opm=equipos&suc=$suc'><img src='lib/deleon.png' alt='Edita reg' border='0'></a></tr>";
//              }
          }else{
            echo "<td align='center'>-</td></tr>";
          }

         $nRng++;
        }
      

      echo "<tr><td align='right'>$Gfont Estructura a Valorar: $Gfon</td><td colspan='4'><TEXTAREA NAME='Estructura' cols='110' rows='2' >$proest[estructura]</TEXTAREA></td></tr>";

      echo "<tr><td align='right'>$Gfont Material necesario: $Gfon</td><td colspan='4'><TEXTAREA NAME='Matyeq' cols='110' rows='2' >$proest[matyeq]</TEXTAREA></td></tr>";

      echo "<tr><td align='right'>$Gfont Preparac. Pac. : $Gfon</td><td colspan='4'><TEXTAREA NAME='Preparacion' cols='110' rows='2' >$proest[preparacion]</TEXTAREA></td></tr>";

      echo "<tr><td align='right'>$Gfont Posicionam. Pac.: $Gfon</td><td colspan='4'><TEXTAREA NAME='Posicion' cols='110' rows='2' >$proest[posicion]</TEXTAREA></td></tr>";

      echo "<tr><td align='right'>$Gfont Tecnica Sugerida: $Gfon</td><td colspan='4'><TEXTAREA NAME='Tecnicaeq' cols='110' rows='2' >$proest[tecnicaeq]</TEXTAREA></td></tr>";

      echo "<tr><td align='right'>$Gfont Post adquision Est.: $Gfon</td><td colspan='4'><TEXTAREA NAME='Postadq' cols='110' rows='2' >$proest[postadq]</TEXTAREA></td></tr>";

      echo "<input type='hidden' name='suc' value=$suc>";
      echo "<input type='hidden' name='idproducto' value=$idproducto>";

      if($Cpo[bloqeqp]=='No'){

        //  if($row4s==$suc or $row4s=='0'){

            echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=equipos&op=cerrareqp&pagina=$pagina&suc=$suc><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a> &nbsp;  &nbsp;  &nbsp; <input type='submit' name='op' value='Actualiza'>&nbsp; &nbsp; ";

        //  }else{

        //    echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=equipos&op=cerrareqp&pagina=$pagina&suc=$suc><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a>";

         // }

      }else{

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=equipos&op=abrireqp&pagina=$pagina&suc=$suc><font size='2'> Abrir </font><img src='images/candadoc.png' alt='pdf' border='0'></a>";
      }

      echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'>";
      echo "<input type='hidden' name='opm' value='equipos'>";
      echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
      echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";

      echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logmodest.php?busca=$busca&opm=equipos&suc=$suc')><font size='1'> *** Modificaciones ***</a>  </td></tr>";

      echo "</table>";   
      echo "</form>"; 

}elseif($opm=='elementos'){

        $colorm='#EDF9FE';

      if($ele=='1'){

        $colorm1='#c4aef1';
        $colorm2='#EDF9FE';
        $colorm3='#EDF9FE';
        $colorm4='#EDF9FE';

      }elseif($ele=='2'){

        $colorm2='#c4aef1';
        $colorm1='#EDF9FE';
        $colorm3='#EDF9FE';
        $colorm4='#EDF9FE';

      }elseif($ele=='3'){

        $colorm3='#c4aef1';
        $colorm1='#EDF9FE';
        $colorm2='#EDF9FE';
        $colorm4='#EDF9FE';

      }

      echo "<table width='80%' border='0' cellpadding='6' cellspacing='0' align='center'>";

      echo "<tr border='1'><td align='center' bgcolor=$colorm1><a href='estudiose2.php?ele=1&busca=$busca&opm=elementos&pagina=$_REQUEST[pagina]'>$Gfont Elementos PDF Standard &nbsp;</font></a></td>
      <td align='center' bgcolor=$colorm2><a href='estudiose2.php?ele=2&busca=$busca&opm=elementos&pagina=$_REQUEST[pagina]'>$Gfont Elementos PDF-Alternativo &nbsp;</font></a></td>
      <td align='center' bgcolor=$colorm3><a href='estudiose2.php?ele=3&busca=$busca&opm=elementos&pagina=$_REQUEST[pagina]'>$Gfont Elementos PDF-Alternativo 2 &nbsp;</font></a></td></tr>";

      echo "</table>";

      echo "<br>";

     filtro($Tablapdf);           #---------------Si trae algo del filtro realizalo ----------------

      if($_REQUEST[op]=='sm'){

           $cSum=mysql_query("select sum($_REQUEST[SumaCampo]) from $Tablapdf ".$cWhe,$link);

           $Suma=mysql_fetch_array($cSum);

           $cFuncion=" // --> $SumaCampo: ".number_format($Suma[0],"2");

      }

      if(!$res=mysql_query($cSqlpdf.$cWhe,$link)){

          cMensaje("No se encontraron resultados ò hay un error en el filtro"); #Manda mensaje de datos no existentes

      }else{

          CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

          $sql=$cSqlpdf.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;
          $res=mysql_query($sql,$link);

          PonEncabezado2();         #---------------------Encabezado del browse----------------------
              echo "<tr bgcolor='#124558'>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Edit</font></td>";            
              echo "<td align='center'>$Gfont <font color='#FFF'>Id</font></td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Descripcion</font></td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Tipo</font></td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Unidad</td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Logitud</td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Dec.</td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Min.</td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Max</td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Logico</td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Texto</td>";
              echo "<td align='center'>$Gfont <font color='#FFF'>Elim</td>";
              echo "</tr>";

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
           
              if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid2;}    //El resto de la division;

              echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
              echo "<td align='center'><a href=estudiose2.php?ele=$ele&busca=$busca&opm=elementos&cEd=$registro[idnvo]&pagina=$pagina><img src='lib/edit.png' alt='Edita reg' border='0'></td>";            
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
                echo "<td align='center'><a href=estudiose2.php?ele=$ele&busca=$busca&opm=elementos&cId=$registro[id]&op=Si&pagina=$pagina><img src='lib/deleon.png' alt='Borra reg' border='0'></td>";
              }else{
                echo "<td align='center'> - </td>";
              }
              echo "</tr>";
              $nRng++;
          }//fin while

          PonPaginacion6(false);      #-------------------pon los No.de paginas-------------------

          echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('resultapdf.php?clnk=$clnk&Estudio=$busca&alterno=$alterno')><font size='1'><img src='pdfenv.png' alt='pdf' border='0'></font></a>  ";

          if($bloqueado=='No'){

              echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=estudiose2.php?ele=$ele&busca=$busca&opm=elementos&op=cerrar&pagina=$pagina>Cerrar <img src='images/candadoa.png' alt='pdf' border='0'></a><font size='2'> Ultima Modificacion por: $usr - $fecha </font>";

          }else{

              echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=estudiose2.php?ele=$ele&busca=$busca&opm=elementos&op=abrir&pagina=$pagina>Abrir <img src='images/candadoc.png' alt='pdf' border='0'></a><font size='2'> Ultima Modificacion por: $usr - $fecha </font>";
          }
    
          echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logelepdf.php?busca=$busca&alterno=$alterno')><font size='1'> *** Modificaciones ***</a>  ";

          echo "<form name='form1' method='get' action=".$_SERVER['PHP_SELF']." onSubmit='return ValCampos();'>";

          echo "<table align='center' width='95%' cellpadding='0' cellspacing='0' border='0'>";

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
             $CpoA  = mysql_query("SELECT * FROM $Tablapdf WHERE idnvo='$_REQUEST[cEd]'");
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

                echo "<td width='50'>&nbsp;<a href='$_SERVER[PHP_SELF]?ele=$ele&busca=$busca&opm=elementos' width='40'><img src='lib/regresar.gif' border='0'></td>";
          }else{

             echo "<td width='50'>$Gfont </td>";
            
          }

          echo "<td>$Gfont ";          
                    
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

          echo "<input type='TEXT' name='Unidad' value='$Cpo[unidad]' size='15' maxlength='15')> &nbsp;";

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
                  echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";
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

          echo "<table align='center' width='95%' cellpadding='0' cellspacing='0' border='0'>";

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

          echo "<table align='center' width='95%' cellpadding='0' cellspacing='0' border='0'>";

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

          echo "<br/><tr><td align='center' colspan='6'>";
          echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'></td></tr>";
          echo "<input type='hidden' name='opm' value='elementos'>";
          echo "<input type='hidden' name='ele' value='$ele'>";
          echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
          echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";
          echo "</table>";

          echo "</form>";

      }    

}elseif($opm=='muestras'){

      echo "<form name='form1' method='get' action='estudiose2.php'>";

      echo "<table width='80%' border='0' align='center' cellpadding='3'>";

      echo "<tr><td align='right'>$Gfont Proceso a realizar $Gfon</td><td align='left' colspan='5'>";

      echo "<SELECT name=Proceso>";
      echo "<option value='TOMA SANGUINEA'>TOMA SANGUINEA</option>";
      echo "<option value='RECOLECCION DE MUESTRA'>RECOLECCION DE MUESTRA</option>";
      echo "<option value='REALIZACION DE ESTUDIOS'>REALIZACION DE ESTUDIOS</option>";
      echo "<option value='TOMA DE MUESTRA CORPORAL'>TOMA DE MUESTRA CORPORAL</option>";
      echo "<option value='SERVICIO'>SERVICIO</option>";
      echo "<option value='MIXTO'>MIXTO</option>";
      echo "<option SELECTed>$Cpo[proceso]</option>";
      echo "</SELECT> <INPUT TYPE='SUBMIT' name='op' value='Agrega_proceso'>";
      echo "</td></tr>";

      $cSql3="SELECT * FROM proc_a_realizar WHERE proc_a_realizar.estudio='$busca'";
      $result3=mysql_query($cSql3);
      while ($row3=mysql_fetch_array($result3)){
      if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

        echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
    
          echo "<td bgcolor='#FFFFFF'></td><td align='left'> $Gfont $row3[proceso] &nbsp; &nbsp; </td>";
        
        if($Cpo[bloqmue]=='No'){
          echo "<td align='center'><a href='estudiose2.php?busca=$busca&id=$row3[id]&proceso=$row3[proceso]&op=Elimproceso&opm=muestras'><img src='lib/deleon.png' alt='Edita reg' border='0'></a></tr>";
        }else{
          echo "<td align='center'>-</td></tr>";
        }

       $nRng++;
      }

      echo "<tr><td align='right'>$Gfont Realizacion $Gfon</td><td> ";

      $nCt=0;
      echo "<SELECT name=Tiempoestd>";
      while ($nCt<=30){
          echo "<option value=$nCt>$nCt</option>";
          $nCt++;
      }
      echo "<option selected>$Cpo[tiempoestd]</option>";
      echo "</SELECT>";

      echo "$Gfont dia(s)</td>";

      echo "<td align='left'>$Gfont Hrs. $Gfon <input name='Tiempoesth' type='text' size='6' value=$Cpo[tiempoesth]></td><td></td></tr>";

      echo "<tr bgcolor='$Gfdogrid'><td align='right'>$Gfont Ordinaria $Gfon</td><td> ";

      $nCt2=0;
      echo "<SELECT name=Entord>";
      while ($nCt2<=30){
          echo "<option value=$nCt2>$nCt2</option>";
          $nCt2++;
      }
      echo "<option selected>$Cpo[entord]</option>";
      echo "</SELECT>";

      echo "$Gfont dia(s)</td>";

      echo "<td align='left'>$Gfont Hrs. $Gfon <input name='Entordh' type='text' size='6' value=$Cpo[entordh]></td></tr>";

      echo "<tr><td align='right'>$Gfont Hospitalizado $Gfon</td><td> ";

      $nCt3=0;
      echo "<SELECT name=Enthosd>";
      while ($nCt3<=30){
          echo "<option value=$nCt3>$nCt3</option>";
          $nCt3++;
      }
      echo "<option selected>$Cpo[enthosd]</option>";
      echo "</SELECT>";

      echo "$Gfont dia(s)</td>";

      echo "<td align='left'>$Gfont Hrs. $Gfon <input name='Enthos' type='text' size='6' value=$Cpo[enthos]></td><td></td></tr>";

      echo "<tr bgcolor='$Gfdogrid'><td align='right'>$Gfont Urgente $Gfon</td><td> ";

      $nCt4=0;
      echo "<SELECT name=Enturgd>";
      while ($nCt4<=30){
          echo "<option value=$nCt4>$nCt4</option>";
          $nCt4++;
      }
      echo "<option selected>$Cpo[enturgd]</option>";
      echo "</SELECT>";

      echo "$Gfont dia(s)</td>";

      echo "<td align='left'>$Gfont Hrs. $Gfon <input name='Enturg' type='text' size='6' value=$Cpo[enturg]></td></tr>";
/*

      echo "<tr><td align='right'>$Gfont Producto $Gfon</td><td align='left' colspan='1'>";

      echo "<input class='Input' type='text' name='Producto' size='20' value='$Producto'> <a href='invlab6.php?orden=invl.descripcion&estudio=$busca'><img src='lib/lupa_o.gif' alt='Catalogo' border='0' width='20' height='20'></a></td>";

      echo "<td align='left'>$Gfont Cant $Gfon <input class='Input' type='text' name='Cantidad' size='4' value=''><INPUT TYPE='SUBMIT' name='op' value='Registrar'></td><td align='left' colspan='2'>";

        $cSql2="SELECT estd.estudio,estd.producto,estd.cantidad,invl.descripcion,invl.clave FROM estd,invl WHERE estd.estudio='$busca' and estd.producto=invl.clave";
        $result2=mysql_query($cSql2);
        while ($row2=mysql_fetch_array($result2)){
        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
      
            echo "<td bgcolor='#FFFFFF'></td><td align='left'> $Gfont $row2[producto] &nbsp; &nbsp; </td><td align='left'>$Gfont $row2[descripcion]</td><td align='center'>$Gfont $row2[cantidad]</td>";
          
          if($Cpo[bloqmue]=='No'){
            echo "<td align='center'><a href='estudiose2.php?busca=$busca&producto=$row2[producto]&op=Elim&opm=muestras'><img src='lib/deleon.png' alt='Edita reg' border='0'></a></tr>";
          }else{
            echo "<td align='center'>-</td></tr>";
          }

         $nRng++;
        }
*/
        echo "<tr><td align='right'>$Gfont Produc. Entregar $Gfon</td><td colspan='4'><TEXTAREA NAME='Producto_entregar' cols='110' rows='2' >$Cpo[producto_entregar]</TEXTAREA></td></tr></br>";

        if($Cpo[dobleinterpreta]=='S'){
            $dobleinter='2';
        }elseif($Cpo[dobleinterpreta]=='N'){
            $dobleinter='1';
        }else{
            $dobleinter='';
        }

        echo "<tr>";
        echo "<td align='right'>$Gfont Interpretaciones $Gfon</td><td>";

        echo "<SELECT name='Dobleinterpreta'>";
        echo "<option value='S'>2</option>";
        echo "<option value='N'>1</option>";
        echo "<option SELECTed value='$Cpo[dobleinterpreta]'>$dobleinter</option>";
        echo "</SELECT>";
        echo "</td>";

        echo "</tr><br/>";

      if($Cpo[bloqmue]=='No'){

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=muestras&op=cerrarmue&pagina=$pagina><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a> &nbsp;  &nbsp;  &nbsp; <input type='submit' name='op' value='Guarda'>&nbsp; &nbsp; ";

      }else{

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=muestras&op=abrirmue&pagina=$pagina><font size='2'> Abrir </font><img src='images/candadoc.png' alt='pdf' border='0'></a>";
      }

      echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'>";
      echo "<input type='hidden' name='opm' value='muestras'>";
      echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
      echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";

      echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logmodest.php?busca=$busca&opm=muestras')><font size='1'> *** Modificaciones ***</a>  </td></tr>";

      echo "</td></tr>";
      echo "</table>";   
      echo "</form>"; 

}elseif($opm=='general'){

      echo "<form name='form1' method='get' action='estudiose2.php'>";

      echo "<table width='80%' border='0' align='center'>";

      echo "<tr><td>$Gfont Objetivo $Gfon</td><td><TEXTAREA NAME='Objetivo' cols='110' rows='5' >$Cpo[objetivo]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Condiciones $Gfon</td><td><TEXTAREA NAME='Condiciones' cols='110' rows='5' >$Cpo[condiciones]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Contenido $Gfon</td><td><TEXTAREA NAME='Contenido' cols='110' rows='5' >$Cpo[contenido]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Observaciones $Gfon</td><td><TEXTAREA NAME='Observaciones' cols='110' rows='5' >$Cpo[observaciones]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Posibles respuesta <br> &nbsp; para radiologia e <br> &nbsp; imagen</td><td><TEXTAREA NAME='Respradiologia' cols='110' rows='10' >$Cpo[respradiologia]</TEXTAREA></td></tr><br/>";


      if($Cpo[bloqdes]=='No'){

          echo "<tr><td align='center' colspan='6'><a href=estudiose2.php?busca=$busca&opm=general&op=cerrardes&pagina=$pagina><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a> &nbsp;  &nbsp;  &nbsp; <input type='submit' name='op' value='Acepta'>&nbsp; &nbsp; ";

      }else{

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=general&op=abrirdes&pagina=$pagina><font size='2'> Abrir </font><img src='images/candadoc.png' alt='pdf' border='0'></a>";
      }

      echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'>";
      echo "<input type='hidden' name='opm' value='general'>";
      echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
      echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";

      echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logmodest.php?busca=$busca&opm=descripcion')><font size='1'> *** Modificaciones ***</a>  </td></tr>";

      echo "</table>";
      echo "</form>"; 

}elseif($opm=='admin'){

    $cSqlist = "SELECT estudio,descripcion,lt1,lt2,lt3,lt4,lt5,lt6,lt7,lt8,lt9,lt10,lt10,lt11,lt12,lt13,lt14,lt15,lt16,lt17,lt18,lt19,lt20,lt21,lt22,lt23,formato,
         modify,fechmod,inv_cunidad,inv_cproducto 
         FROM $Tabla 
         WHERE estudio='$busca'";

    $CpoAlist  = mysql_query($cSqlist);
    $Cpolist   = mysql_fetch_array($CpoAlist);

        $ct_ps_q = mysql_query("SELECT C.nombre, CT.descripcion , CT.tipo
    FROM cfdi33_c_conceptos C
    JOIN cfdi33_c_categorias CT 
          ON (CT.clave_padre = '0' AND CT.clave = CONCAT(SUBSTR(C.clave, 1, 2), '000000')) 
          OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 4), '0000') 
          OR CT.clave = CONCAT(SUBSTR(C.clave, 1, 6), '00')
    WHERE C.clave = '" . $Cpolist['inv_cproducto'] . "'
    ORDER BY CT.clave
    ");

    $lAg   = $busca<>$Cpolist[estudio];
    
    echo "<table width=85% border='0' align='center'>";
   
    echo "<form name='form1' method='get' action='estudiose2.php'>";
        
    $rubro      = $queryParameters['Rubro']!='' ? $queryParameters['Rubro'] : ($Cpolist['rubro']!='' ? $Cpolist['rubro'] : "Aceites");
    $umedida    = $queryParameters['Umedida']!='' ? $queryParameters['Umedida'] : ($Cpolist['umedida']!='' ? $Cpolist['umedida'] : "Pzas");
    $activo     = $queryParameters['Activo']!='' ? $queryParameters['Activo'] : ($Cpolist['activo']!='' ? $Cpolist['activo'] : "Si");

    ?>

        <tr bgcolor='#05495f'><td align='center' width='15%'>
        <div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFFF"><b>Precios</b></div>
        </td><td align='center'><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#FFFFFF"><b>Instituciones</b></div></td></tr>

        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 1........:<input type="text" name="Lt1" value ='<?php echo $Cpolist[lt1]; ?>' size="5"></div></td> 
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=1 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>

        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 2........:<input type="text" name="Lt2" value ='<?php echo $Cpolist[lt2]; ?>' size="5"></div></td> 

        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=2 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 3........:<input type="text" name="Lt3" value ='<?php echo $Cpolist[lt3]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=3 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr> 
        
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 4........:<input type="text" name="Lt4" value ='<?php echo $Cpolist[lt4]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=4 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 5........:<input type="text" name="Lt5" value ='<?php echo $Cpolist[lt5]; ?>' size="5"></div></td> 
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=5 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 6........:<input type="text" name="Lt6" value ='<?php echo $Cpolist[lt6]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=6 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>

        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 7........:<input type="text" name="Lt7" value ='<?php echo $Cpolist[lt7]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=7 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr> 

        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 8........:<input type="text" name="Lt8" value ='<?php echo $Cpolist[lt8]; ?>' size="5"></div></td> 
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=8 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>

        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 9........:<input type="text" name="Lt9" value ='<?php echo $Cpolist[lt9]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=9 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>

        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 10......:<input type="text" name="Lt10" value ='<?php echo $Cpolist[lt10]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=10 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr> 
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 11......:<input type="text" name="Lt11" value ='<?php echo $Cpolist[lt11]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=11 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
         
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 12......:<input type="text" name="Lt12" value ='<?php echo $Cpolist[lt12]; ?>' size="5"></div></td> 
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=12 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 13......:<input type="text" name="Lt13" value ='<?php echo $Cpolist[lt13]; ?>' size="5"></div></td> 
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=13 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        <div></td></tr>
        
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 14......:<input type="text" name="Lt14" value ='<?php echo $Cpolist[lt14]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=14 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 15......:<input type="text" name="Lt15" value ='<?php echo $Cpolist[lt15]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=15 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 16......:<input type="text" name="Lt16" value ='<?php echo $Cpolist[lt16]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=16 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 17......:<input type="text" name="Lt17" value ='<?php echo $Cpolist[lt17]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=17 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 18......:<input type="text" name="Lt18" value ='<?php echo $Cpolist[lt18]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=18 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 19......:<input type="text" name="Lt19" value ='<?php echo $Cpolist[lt19]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=19 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 20......:<input type="text" name="Lt20" value ='<?php echo $Cpolist[lt20]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=20 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr> <td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 21......:<input type="text" name="Lt21" value ='<?php echo $Cpolist[lt21]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=21 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
       
        <tr bgcolor='#becbf0'><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 22......:<input type="text" name="Lt22" value ='<?php echo $Cpolist[lt22]; ?>' size="5"></div></td>
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=22 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>
        
        <tr><td><div><font face="Verdana, Arial, Helvetica, sans-serif" size="2" color="#003399">    
        Precio 23......:<input type="text" name="Lt23" value ='<?php echo $Cpolist[lt23]; ?>' size="5"></div></td> 
        <td align='left' width='750'>
        <div>
            <?php
                $result=mysql_query("select institucion,alias from inst where lista=23 and status='ACTIVO'",$link);
                while ($row=mysql_fetch_array($result)){
                      echo "$Gfont  &diams;  $row[institucion] $row[alias] &nbsp; </font> ";
                }
            ?>
        </div></td></tr>

       </table>
        
        <script type="text/javascript" src="js/jquery-1.8.2.min.js"></script>

        <script>

            $(document).ready(function () {
                $('#cumedida').val('<?= $cumedida != '' ? $cumedida : $Cpolist['inv_cunidad'] ?>');
                $('#common_claveps').val('<?= $common_claveps != '' ? $common_claveps : $Cpolist['inv_cproducto'] ?>');
            });

        </script>

    <?php
        echo "<table width=70% border='0' align='center'>";

      if($Cpo[bloqadm]=='No'){

          echo "<tr><td align='center' colspan='6'><a href=estudiose2.php?busca=$busca&opm=admin&op=cerraradm&pagina=$pagina><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a> &nbsp;  &nbsp;  &nbsp; <input type='submit' name='op' value='GuardaPrec'>&nbsp; &nbsp; ";

      }else{

          echo "<tr><td align='center' colspan='6'><a href=estudiose2.php?busca=$busca&opm=admin&op=abriradm&pagina=$pagina><font size='2'> Abrir </font><img src='images/candadoc.png' alt='pdf' border='0'></a>";
      }

      echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'>";
      echo "<input type='hidden' name='opm' value='admin'>";
      echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";

      echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logmodest.php?busca=$busca&opm=admin')><font size='1'> *** Modificaciones ***</a>  </td></tr>";   

        echo "</table>";
        echo "</form>"; 
   
        echo "<form name='form1' method='get' action='estudiose2.php'>";
        echo "<br><table width='80%' align='center' border='1' cellpadding='2' cellspacing='1' bgcolor='#f1f1f1' style='border-collapse: collapse; border: 1px solid #999;'>";

        echo "<tr><td colspan='2' align='left'  class='content_txt'> &nbsp; *** Clasificacion necesario para poder facturar &eacute;ste producto</td></tr>";     

        echo '<tr class="content_txt">';
        echo '<td class="content_txt" align="right" bgcolor="#E1E1E1">';
        echo 'Unidad de medida: ';
        echo '</td><td class="content_txt">';
        ComboboxUnidades::generate('cumedida');
        echo "</td></tr>";

        echo '<tr class="content_txt">';
        echo '<td class="content_txt" align="right" bgcolor="#E1E1E1">';
        echo 'Clave de Producto/Servicio: ';
        echo '</td><td class="content_txt">';

        ComboboxCommonProductoServicio::generate("common_claveps"); 

        echo "&nbsp; <a href='categoriasSAT.php?busca=$busca'>";
        echo '<img src="lib/desplegar.png" title="en caso de no aparecer la clave del nuevo producto dar click aqui">';                                
        echo "</a></td></tr>";

        echo "<tr><td></td><td align='right'>";
        if($Cpo[bloqadm]=='No'){
          echo "<input class='InputBoton' type='submit' name='op' value='Actualiza/clasificacion'>";
        }
        echo " &nbsp; </td></tr></table>";
        echo "<input type='hidden' name='opm' value='admin'>";
        echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
        echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";                               
        echo "</form>";

}elseif($opm=='promocion'){

      echo "<form name='form1' method='get' action='estudiose2.php'>";

      echo "<table width='80%' border='0' align='center'>";

      echo "<tr><td>$Gfont Ventajas Competitivas $Gfon</td><td><TEXTAREA NAME='Ventajas' cols='110' rows='5' >$Cpo[ventajas]</TEXTAREA></td></tr>";

      echo "<tr><td>$Gfont Promocion General / Estudios Sugeridos $Gfon</td><td><TEXTAREA NAME='Promogral' cols='110' rows='5' >$Cpo[promogral]</TEXTAREA></td></tr></br>";

      echo "<tr><td>$Gfont Mensaje Administrativo $Gfon</td><td><TEXTAREA NAME='Msjadmvo' cols='110' rows='5' >$Cpo[msjadmvo]</TEXTAREA></td></tr></br>";

      if($Cpo[bloqatn]=='No'){

          echo "<tr><td align='center' colspan='6'><a href=estudiose2.php?busca=$busca&opm=promocion&op=cerraratn&pagina=$pagina><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a> &nbsp;  &nbsp;  &nbsp; <input type='submit' name='op' value='GuardaPromo'>&nbsp; &nbsp; ";

      }else{

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=promocion&op=abriratn&pagina=$pagina><font size='2'> Abrir </font><img src='images/candadoc.png' alt='pdf' border='0'></a>";
      }

      echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'>";
      echo "<input type='hidden' name='opm' value='promocion'>";
      echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
      echo "<input type='hidden' name='pagina' value='$_REQUEST[pagina]'>";

      echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logmodest.php?busca=$busca&opm=atn')><font size='1'> *** Modificaciones ***</a>  </td></tr>";
          
      echo "</table>";
      echo "</form>"; 

}elseif($opm=='contenido'){

        echo "<form name='form1' method='get' action='estudiose2.php'>";

      echo "<table width='70%' border='0' align='center'>";

      echo "<tr><td align='left'>$Gfont Estudio $Gfon";

     if($Cpo[bloqcon]=='No'){


          echo "<input type='hidden' name='opm' value='contenido'>";

          echo "<a href='agrestcont.php?pagina=0&Sort=Asc&estudio=$busca&busca=&orden=est.estudio'><img src='lib/lupa_o.gif' alt='Catalogo' border='0' width='20' height='20'></a></td>";

      }

        $cSql3="SELECT conest.id,conest.estudio,conest.conest,conest.descripcion FROM conest WHERE conest.estudio='$busca'";
        $result3=mysql_query($cSql3);
        while ($row3=mysql_fetch_array($result3)){
        if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

          echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";

          echo "<td bgcolor='#FFFFFF'></td><td align='left'>$Gfont $row3[conest]  -  $row3[descripcion]</td>";
               if($Cpo[bloqcon]=='No'){

                echo "<td align='center'><a href='estudiose2.php?busca=$busca&id=$row3[id]&op=Eliest&opm=contenido'><img src='lib/deleon.png' alt='Edita reg' border='0'></a></tr>";
              }

         $nRng++;
        }

      echo "</td></tr>";

      if($Cpo[bloqcon]=='No'){

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=contenido&op=cerrarcon&pagina=$pagina><font size='2'> Cerrar </font><img src='images/candadoa.png' alt='pdf' border='0'></a> &nbsp;  &nbsp;  &nbsp;";

      }else{

          echo "<tr><td align='center' colspan='4'><a href=estudiose2.php?busca=$busca&opm=contenido&op=abrircon&pagina=$pagina><font size='2'> Abrir </font><img src='images/candadoc.png' alt='pdf' border='0'></a>";
      }

      echo "&nbsp; &nbsp; <input type='submit' name='Boton' value='Salir'>";
      echo "<input type='hidden' name='opm' value='contenido'>";
      echo "<input type='hidden' name='busca' value='$_REQUEST[busca]'>";
      echo "<input type='hidden' name='pagina' value='$pagina'>";

      echo " &nbsp;  &nbsp;  &nbsp;  &nbsp; <a href=javascript:wingral('logmodest.php?busca=$busca&opm=contenido')><font size='1'> *** Modificaciones ***</a></td></tr>";

      echo "</form>"; 
      echo "</tr>";
      echo "</table>"; 
      
      echo "</table>";
}

      echo "<table width='70%' border='0' align='center'>";

      echo "<tr><td align='center'>$Gfont <b>Usr.alta: </b> $Cpo[agrego] &nbsp;&nbsp; <b>Fecha Alta:</b> $Cpo[fechalta] &nbsp;&nbsp;&nbsp; <b>Usr.ult.mod:</b> $Cpo[modify] &nbsp;&nbsp; <b>Fecha Modif.:</b> $Cpo[fechmod]</td></tr>";

    echo "</font>";

    echo "$Gfont <tr><td align='center'><a href=javascript:wingral('estudiopdf.php?busca=$busca')> <img src='images/print.gif' alt='pdf' border='0'></a></td></tr>";
      echo "</table>";
	?>

  </td>
  </tr>
</table>
</body>
</html>
<?
mysql_close();
?>