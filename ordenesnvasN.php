<?php

  session_start();

  include_once ("auth.php");

  include_once ("authconfig.php");

  include_once ("check.php");

  require("lib/libN.php");
  
  date_default_timezone_set("America/Mexico_City");
  $Linea  = array('','UNO','DOS','TRES','CUATRO','CINCO');

  $Fec    = date('Y-m-d');
  $Usr    = $_COOKIE['USERNAME'];
  $Suc    = $_COOKIE['TEAM'];        //Sucursal 
  if($Suc=='0'){
    $suc2=1;
  }else{
    $suc2=$Suc;
  }
  if(!isset($_REQUEST['Sucent'])){$Sucent=$suc2;}else{$Sucent=$_REQUEST['Sucent'];}            //Sucursal de entrega

  $tentregamed=$_REQUEST['tentregamed'];

  $tentregadig=$_REQUEST['tentregadig'];

  $link   = conectarse();

  $tamPag = 14;

  $pagina = $_REQUEST[pagina];
  
  // $Vta = $_REQUEST[Vta];

  if(!isset($_REQUEST[Vta])){$Vta=$_SESSION['Venta_ot'];}else{

     $Vta = $_REQUEST[Vta];

     $_SESSION['Venta_ot']=$_REQUEST[Vta];
	 
  } #En caso k venga del cat.de clientes(cliventas) y desde ahi manda la clave
  
  $Vta = $_REQUEST[Vta];

  $op      = $_REQUEST[op];

  $busca   = $_REQUEST[busca];

  $OrdenDef= "estudio";      //Orden de la tabla por default

  $Estudio = strtoupper($_REQUEST[Estudio]);

  if($op=="conte"){      //Cuando entras p agregar el encabezado

          header("Location: agrestordN.php?Vta=$Vta&Usr=$Usr&orden=est.descripcion&Inst=$_REQUEST[Inst]&contenido=$_REQUEST[Contenido]");

  }elseif($op=="pr"){      //Cuando entras p agregar el encabezado
  //if($op=="pr"){      //Cuando entras p agregar el encabezado

     $Fecha  = date("Y-m-d");
     $InsA   = mysql_query("SELECT lista,descuento,condiciones,msjadministrativo,enviomail FROM inst WHERE institucion='$_REQUEST[Institucion]'",$link);    #Checo la lista de la institucion
     $Ins    = mysql_fetch_array($InsA);

     if($Ins[enviomail]=='Si'){
        $envmailinst=5;
        $envmailinst2=1;
     }else{
        $envmailinst=0;
        $envmailinst2=0;
     }

     $Upd    = mysql_query("UPDATE otnvas set inst='$_REQUEST[Institucion]',lista='$Ins[0]',tentregamost='$Sucent',tentregadig='$envmailinst',entemailinst='$envmailinst2' WHERE usr='$Usr' and venta='$Vta'",$link);
     $UpdA   = mysql_query("SELECT * FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
     $Upd    = mysql_fetch_array($UpdA);
     if(!$Upd[usr]=="$Usr"){
        $lUp = mysql_query("INSERT INTO otnvas (inst,lista,usr,venta,servicio,fechae) VALUES ('1','$Ins[0]','$Usr','$Vta','Ordinaria','$Fecha')",$link);
     }
	 
	 if($Ins[msjadministrativo]<>''){
		$mss=$Ins[msjadministrativo];
		echo '<script language="javascript">alert("'.$mss.'");</script>'; 
	 }

  }elseif($op=='Estudio'){        // Agrega Estudios a la Venta

     $returnValuest = strpos($Estudio, '- ');
     if($returnValuest==False){
          $Estudio = $Estudio;
     }else{
          $returnValuest = $returnValuest -1;
          $Estudio = substr($Estudio, 0, $returnValuest);  
     }


    $OtnvaA   = mysql_query("SELECT lista,fechae,servicio,inst FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    if($Otnva = mysql_fetch_array($OtnvaA)){

        $Fecha = date("Y-m-d");
        $Fecha = strtotime($Fecha);
        $Lista = "lt".ltrim($Otnva[lista]);

        if($Otnva[lista] <> 0){
		
		   $InsB   = mysql_query("SELECT lista,descuento FROM inst WHERE institucion='$Otnva[inst]'",$link);    #Checo la lista de la institucion
		   $Ins2    = mysql_fetch_array($InsB);


           $EstA   = mysql_query("SELECT estudio,descripcion,$Lista,entord,entexp,enthos,enturg,msjadmvo
                     FROM est
                     WHERE estudio='$Estudio' ");

           $cCpo    = mysql_fetch_array($EstA);
           
           if($cCpo[estudio] <> ''){
			   
               if( $cCpo[$Lista] > 0){
               
//                    if($Otnva[servicio]=="Ordinaria"){$Dias=$cCpo[entord];}else{$Dias=$cCpo[entexp]/24;}
                    if($Otnva[servicio]=="Ordinaria"){$Dias=$cCpo[entord];}else{$Dias=$cCpo[entord];}
					
					$nDias   = strtotime("$Dias days",$Fecha);     //puede ser days month years y hasta -1 month menos un mes...
                    $Fechaest  = date("Y-m-d",$nDias);

                    $lUp     = mysql_query("INSERT INTO otdnvas (usr,estudio,descripcion,descuento,precio,venta,fechaest)
                               VALUES
                               ('$Usr','$Estudio','$cCpo[1]','$Ins2[descuento]','$cCpo[$Lista]','$Vta','$Fechaest')");

                    $nDias   = strtotime("$Dias days",$Fecha);     //puede ser days month years y hasta -1 month menos un mes...
                    $Fechae  = date("Y-m-d",$nDias);
                    $Fechaest  = date("Y-m-d",$nDias);
                    
                    if($Otnva[fechae] < $Fechae ){   //Checa y autaliza de fecha de entrada
                        $Otnva = mysql_query("UPDATE otnvas set fechae = '$Fechae' WHERE usr='$Usr' and venta='$Vta'",$link);
                    }

                }else {

                   $Msj = "Precio en ceros, favor de verificar";
                   
               }
               
           }else{
              $Msj = "El Estudio [$Estudio] no existe, favor de verificar";
           }
        }else{
              $Msj = "Aun no has elegido la institucion, favor de verificar";
        }
     }else{
        $Msj = "Aun no as elegido la Institucion(lista/precios), favor de verificar";
     }

    if($cCpo[msjadmvo]<>''){
      $mss=$cCpo[msjadmvo];
      echo '<script language="javascript">alert("'.$mss.'");</script>'; 
    }
  }elseif($op=="br"){  // borra todo

    $borrtodo=$_REQUEST[bt];
   
    if($borrtodo<>'si'){
      echo "<script>window.open('impotpdf-ant.php?busca=$_REQUEST[id]','_blank','height=800,width=1000');</script>";
    }

     $Fecha  = date("Y-m-d");
     $Horae  = "15:00";
     $lUp    = mysql_query("delete FROM otdnvas WHERE usr='$Usr' and venta=$Vta",$link);
     $lUp    = mysql_query("delete FROM otnvas WHERE usr='$Usr' and venta=$Vta",$link);
     $InsA   = mysql_query("SELECT lista FROM inst WHERE institucion='1'",$link);    #Checo la lista de la institucion
     $Ins    = mysql_fetch_array($InsA);
     $lUp    = mysql_query("INSERT INTO otnvas (inst,lista,usr,venta,servicio,fechae,horae)
               VALUES
               ('0','0','$Usr','$Vta','Ordinaria','$Fecha','$Horae')");

  }elseif($op=="cl"){ //CLiente

    if(is_numeric($_REQUEST[Cliente])) {

         $CliA    = mysql_query("SELECT cliente,msjadv,programa,numveces FROM cli WHERE cliente='$_REQUEST[Cliente]'",$link);

         if($Cli=mysql_fetch_array($CliA)){
             $lUp = mysql_query("UPDATE otnvas set cliente = '$_REQUEST[Cliente]' WHERE usr='$Usr' and venta='$Vta'",$link);

            $programa=$Cli[programa];

            $numveces=$Cli[numveces];

           if($Cli[msjadv]<>'' or $programa<>0){

              $mssadv=$Cli[msjadv];

              if($programa==1){
                  $mssadv=$mssadv." - Pertenece al programa: Cliente frecuente - ";
              }elseif($programa==2){
                  $mssadv=$mssadv." - Pertenece al programa: Apoyo a la salud - ";
              }elseif($programa==3){
                  $mssadv=$mssadv." - Pertenece al programa: Chequeo medico - ";
              }elseif($programa==4){
                  $mssadv=$mssadv." - Pertenece al programa: Empleado - ";
              }elseif($programa==5){
                  $mssadv=$mssadv." - Pertenece al programa: Familiar - ";
              }elseif($programa==6){
                  $mssadv=$mssadv." - Pertenece al programa: Medico - ";
              }elseif($programa==7){
                  $mssadv=$mssadv." - Pertenece al programa: Especializado - ";
              }

              echo '<script language="javascript">alert("'.$mssadv.'");</script>'; 

           }

            if($numveces>=10 and $programa==0){
                $mssadv=$mssadv." - Actualizar registro a CLIENTE FRECUENTE - ";

                echo '<script language="javascript">alert("'.$mssadv.'");</script>'; 
            }


         }else{
             $Msj = "El No. de paciente ".$Cliente." no existe";
         }

    }else{

          header("Location: clientesordN.php?Vta=$_REQUEST[Vta]&Vta=$Vta&pagina=1&busca=$_REQUEST[Cliente]");

    }


  }elseif($op=="md"){ //Med
     $Medico = strtoupper($_REQUEST[Medico]);
     $returnValue = strpos($Medico, '-');
     if($returnValue==False){
          $rest = $Medico;
     }else{
          $returnValue = $returnValue -1;
          $rest = substr($Medico, 0, $returnValue);  
     }

     $MedA   = mysql_query("SELECT medico,status,enviomail FROM med WHERE medico='$rest' and status<>'Baja' and status<>'Defuncion'",$link);
     if($Med=mysql_fetch_array($MedA)){
           if($Med[enviomail]=='Si'){
              $envmailmed=3;
              $envmailmed2=1;
           }else{
              $envmailmed=0;
              $envmailmed2=0;
           }
        $lUp=mysql_query("UPDATE otnvas set medico = '$rest',tentregadig='$envmailmed',entemailmed='$envmailmed2' WHERE usr='$Usr' and venta='$Vta'",$link);
     }else{
        $Msj="La Clave del Medico ".$Medico ." no existe, Baja o Defuncion...";
                //$Msj=$rest;

     }
  }elseif($op=="med"){ //Med
     $Medicon = strtoupper($_REQUEST[Medicon]);
     $lUp=mysql_query("UPDATE otnvas set medicon = '$Medicon' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Eli"){ //Elimina     $Medico=strtoupper($Medico);
    $lUp=mysql_query("delete FROM otdnvas WHERE usr='$Usr' and venta='$Vta' and estudio='$busca' limit 1",$link);
  }elseif($op=="rec"){
    $lUp=mysql_query("UPDATE otnvas set receta = '$_REQUEST[Receta]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="dato"){
    $lUp=mysql_query("UPDATE otnvas set datoc = '$_REQUEST[Datoc]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="reseco"){
    $lUp=mysql_query("UPDATE otnvas set responsableco = '$_REQUEST[Reseco]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="frec"){
    $lUp=mysql_query("UPDATE otnvas set fechar='$_REQUEST[Fechar]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Rc"){
    $lUp=mysql_query("UPDATE otnvas set receta = '$_REQUEST[Receta]',fechar='$_REQUEST[Fechar]',abono=$_REQUEST[Abono] WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="dtg"){ //Aplica el descuento gral.
    $lUp=mysql_query("UPDATE otdnvas set descuento = '$_REQUEST[Descuento]' WHERE usr='$Usr' and venta='$Vta'",$link);
    $lUp=mysql_query("UPDATE otnvas set descuento = '$_REQUEST[Razon]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="dt"){ //Aplica el descuento detalle por estudio
    $lUp=mysql_query("UPDATE otdnvas set descuento = '$_REQUEST[Descuento]' WHERE usr='$Usr' and venta='$Vta' and estudio='$Estudio'",$link);
    $lUp=mysql_query("UPDATE otnvas set descuento = '$_REQUEST[Razon]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="ab"){
	$AbA    = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otdnvas WHERE usr='$Usr' and venta='$Vta'");
	$Abo     = mysql_fetch_array($AbA);
	$imp = $Abo[0];
	 if($_REQUEST[Abono]>=0 and $_REQUEST[Abono]<=$imp){
    	$lUp=mysql_query("UPDATE otnvas  set abono='$_REQUEST[Abono]' WHERE usr='$Usr' and venta='$Vta'",$link);
     }else{
        $Msj="Importe Incorrecto Verificar...";
     }
  }elseif($op=="tp"){
    $lUp=mysql_query("UPDATE otnvas  set tpago='$_REQUEST[Tpago]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="do"){
    $lUp=mysql_query("UPDATE otnvas  set diagmedico='$_REQUEST[Diagmedico]' WHERE usr='$Usr' and venta='$Vta'",$link);
    $lUp=mysql_query("UPDATE otnvas  set observaciones='$_REQUEST[Observaciones]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Sv"){
    $lUp=mysql_query("UPDATE otnvas  set servicio='$_REQUEST[Servicio]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="most"){
    $lUp=mysql_query("UPDATE otnvas  set tentregamost ='$_REQUEST[tentregamost]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="medi"){
    $lUp=mysql_query("UPDATE otnvas  set tentregamed ='$_REQUEST[tentregamed]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="insti"){
    $lUp=mysql_query("UPDATE otnvas  set tentregainst ='$_REQUEST[tentregainst]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="digi"){
    $lUp=mysql_query("UPDATE otnvas  set tentregadig ='$_REQUEST[tentregadig]', entemailpac='$_REQUEST[entemailpac]',entemailmed='$_REQUEST[entemailmed]',entemailinst='$_REQUEST[entemailinst]',entwhatpac='$_REQUEST[entwhatpac]',entwhatmed='$_REQUEST[entwhatmed]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Fc"){   // Cambai fecha de etrega
    $lUp=mysql_query("UPDATE otnvas set fechae = '$_REQUEST[Fechae]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Fc1"){   // Cambai hora de etrega
    $lUp=mysql_query("UPDATE otnvas set horae = '$_REQUEST[Horae]' WHERE usr='$Usr' and venta='$Vta'",$link);
  }elseif($op=="Fn"){
    $Ot1 = mysql_query("SELECT * FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $Ot2 = mysql_fetch_array($Ot1);
    $Ot3 = $Ot2[cliente];
	  $Ot4 = $Ot2[medico];
    $Ot5 = $Ot2[inst];
	  if($Ot3=='0'){
        $Msj="Falta Cliente Verifique ...";	  
    }elseif($Ot4==''){
        $Msj="Falta Medico Verifique ...";   
    }elseif($Ot5=='0'){
        $Msj="Falta Institucion Verifique ...";    
	  }else{
    	header("Location: impotpdf2.php?Usr=$Usr&Vta=$Vta");	 
    } 
  }elseif($op=="Fnot"){               // Genera la Orden de trabajo
            
    $Folio   = cZeros(IncrementaFolio('otfolio',$Suc),5);
    
    $Folioc  = cZeros(IncrementaFolio('cajafolio',$Suc),5);                   

    $Fecha = date("Y-m-d");
    $Hora1 = date("H:i");
    $hora = date("H:i");            //Si pongo H manda 17:30, si pongo h manda 5:30
    $OtdA = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otdnvas WHERE usr='$Usr' and venta='$Vta'");
    $Otd = mysql_fetch_array($OtdA);
    $OtA = mysql_query("SELECT * FROM otnvas WHERE usr='$Usr' AND venta='$Vta'", $link);
    $Ot = mysql_fetch_array($OtA);
    $AboA = mysql_query("SELECT abono FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $Abo = mysql_fetch_array($AboA);
	  $Abono = $Abo[abono];
    $cPag = 'No';

    if ($Abono + .5 >= $Otd[0]) {
        $cPag = 'Si';
    }
	
	if ($Ot[medico]<>''){
		$medico=$Ot[medico];
	}else{
		$medico="AQ";
	}
	
    $lUp = mysql_query("INSERT INTO ot
          (cliente,fecha,hora,medico,fecharec,fechae,institucion,diagmedico,observaciones,servicio,recepcionista,
          receta,importe,descuento,pagada,fecpago,medicon,status,horae,suc,folio,datoc,tentregamost,tentregamed,tentregadig,entemailpac,entemailmed,entemailinst,entwhatpac,entwhatmed,idprocedencia,responsableco)
          VALUES
          ('$Ot[cliente]','$Fecha','$hora','$medico','$Ot[fechar]','$Ot[fechae]','$Ot[inst]',
          '$Ot[diagmedico]','$Ot[observaciones]','$Ot[servicio]','$Usr','$Ot[receta]',$Otd[0],
          '$Ot[descuento]','$cPag','$Fecha','$Ot[medicon]','DEPTO','$Ot[horae]','$Suc','$Folio','$Ot[datoc]','$Ot[tentregamost]','$Ot[tentregamed]','$Ot[tentregadig]','$Ot[entemailpac]','$Ot[entemailmed]','$Ot[entemailinst]','$Ot[entwhatpac]','$Ot[entwhatmed]','$Ot[idprocedencia]','$Ot[responsableco]')");
    
    $Id = mysql_insert_id();

    $lUp = mysql_query("UPDATE cli SET numveces=numveces+1 WHERE cliente='$Ot[cliente]' LIMIT 1");

    $lUpA = mysql_query("SELECT otdnvas.estudio,otdnvas.precio,otdnvas.descuento,est.depto,otdnvas.fechaest
            FROM otdnvas,est
            WHERE usr='$Usr' and venta='$Vta' and otdnvas.estudio=est.estudio");    #Checo k bno halla estudios capturados

    $lBd = false;

    while ($lUp = mysql_fetch_array($lUpA)) {
        $Depto = 'DEPTO'; //kda en el depto
        $PreA = mysql_query("SELECT estudio,pre FROM pred WHERE estudio='$lUp[0]'");  //Si existe en preanaliticos
        while ($lPre = mysql_fetch_array($PreA)) {  //Si tiene pregts.de pre-analitico los da de alta en otpre
            $lBd = true;
            $lEx = mysql_query("INSERT INTO otpre (orden,estudio,pregunta) VALUES ($Id,'$lUp[0]','$lPre[1]')");
        }

        $lOtd = mysql_query("INSERT INTO otd (orden,estudio,precio,descuento,status,fechaest1)
                 VALUES
                 ($Id,'$lUp[estudio]','$lUp[precio]','$lUp[descuento]','$Depto','$lUp[fechaest]')");

        if ($lUp[depto] == 2) {                  // Si es que es de radiologia se crea un archivo en base a un formato del word y lo copio
            $FilWord = strtolower("informes/" . $lUp[estudio] . ".doc");
            $FilOut = strtolower("textos/" . $lUp[estudio] . $Id . ".doc");

            if (file_exists($FilWord)) {
                copy($FilWord, $FilOut);
            }
        }
    }
    if ($Abono > 0) {
        $nAb = $Abono;
    } else {
        $nAb = .5;
    }
	
    $Tpag = mysql_query("SELECT tpago FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $Tpago1 = mysql_fetch_array($Tpag);
	
	if ($Tpago1[0]<>''){
    	$Tpago = $Tpago1[0];
	}else{
		$Tpago="Efectivo";
	}
	
//    $Tpago = $_REQUEST[Tpago];

    $lUp = mysql_query("INSERT INTO cja (orden,fecha,hora,usuario,importe,tpago,suc,folio)
    		    VALUES
    		    ($Id,'$Fecha','$hora','$Usr','$nAb','$Tpago','$Suc','$Folioc')");

   header("Location: ordenesnvasN.php?op=br&id=$Id&Vta=$Vta");

}elseif($op=="ambu"){
    $ambuA = mysql_query("SELECT idprocedencia FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $amb = mysql_fetch_array($ambuA);
    $amb = $amb[idprocedencia];

    if($amb=='ambu'){
      $op='';
    }else{
      $op=$op;
    }

    $lUp=mysql_query("UPDATE otnvas set idprocedencia = '$op' WHERE usr='$Usr' and venta='$Vta'",$link);

}elseif($op=="silla"){
    $ambuA = mysql_query("SELECT idprocedencia FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $amb = mysql_fetch_array($ambuA);
    $amb = $amb[idprocedencia];

    if($amb=='silla'){
      $op='';
    }else{
      $op=$op;
    }

    $lUp=mysql_query("UPDATE otnvas set idprocedencia = '$op' WHERE usr='$Usr' and venta='$Vta'",$link);

}elseif($op=="terceraedad"){
    $ambuA = mysql_query("SELECT idprocedencia FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $amb = mysql_fetch_array($ambuA);
    $amb = $amb[idprocedencia];

    if($amb=='terceraedad'){
      $op='';
    }else{
      $op=$op;
    }

    $lUp=mysql_query("UPDATE otnvas set idprocedencia = '$op' WHERE usr='$Usr' and venta='$Vta'",$link);
  
}elseif($op=="problemasv"){
    $ambuA = mysql_query("SELECT idprocedencia FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $amb = mysql_fetch_array($ambuA);
    $amb = $amb[idprocedencia];

    if($amb=='problemasv'){
      $op='';
    }else{
      $op=$op;
    }
    
    $lUp=mysql_query("UPDATE otnvas set idprocedencia = '$op' WHERE usr='$Usr' and venta='$Vta'",$link);

}elseif($op=="bebe"){

    $ambuA = mysql_query("SELECT idprocedencia FROM otnvas WHERE usr='$Usr' and venta='$Vta'");
    $amb = mysql_fetch_array($ambuA);
    $amb = $amb[idprocedencia];

    if($amb=='bebe'){
      $op='';
    }else{
      $op=$op;
    }
    
    $lUp=mysql_query("UPDATE otnvas set idprocedencia = '$op' WHERE usr='$Usr' and venta='$Vta'",$link);
}

$MsA    = mysql_query("SELECT count(*) FROM msj WHERE para='$Usr' AND !bd");
$Ms     = mysql_fetch_array($MsA);
$nMsj   = $Ms[0];

$HeA    = mysql_query("SELECT sum(precio*(1-descuento/100)) FROM otdnvas WHERE usr='$Usr' and venta='$Vta'");
$He     = mysql_fetch_array($HeA);

$cSql   = "SELECT estudio,descripcion,precio,descuento,fechaest
		FROM otdnvas
		WHERE usr='$Usr' and venta='$Vta'";

$Edit   = array("Est","Descripcion","Precio","<a class='vt' href='descuentosN.php?Vta=$Vta&op=dtg'>%Dto</a>","Importe","Elim","T/Entreg","Status","Etq","Obs","-","-","-","-","-","-","-","-","-","-");

$aPrg = array('Ninguno','Cliente frecuente','Apoyo a la salud','Chequeo medico','Empleado','Familiar','Medico','Especializado');

require ("configN.php");

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>

<script language="JavaScript" src="js/jquery-1.5.1.min.js"></script>
<script language="JavaScript" src="js/jquery-ui-1.8.13.custom.min.js"></script>
<link type="text/css" href="css/ui-lightness/jquery-ui-1.8.13.custom.css" rel="stylesheet" />

<title><?php echo $Titulo;?></title>

</head>


<?php

	echo "<body bgcolor='#FFFFFF' leftmargin='$MagenIzq' topmargin='$MargenAlt' marginwidth='$MargenIzq' marginheight='$MargenAlt' onload='cFocus()'>";
	
	echo "<table width='100%' border='0'>";
	
	echo "<tr>";
	
	echo "<td width='15%' align='left'><a href='menu.php'><img  src='lib/logo2.jpg' border='0'></a></td>";
	
	echo "<td>&nbsp;</td>";

	echo "<td width=91><img  src='lib/logo40.jpg' border='0'></td>";
	
	echo "</tr></table>";

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

function cFocus(){
  document.form2.Estudio.focus();
}

function vacio(q) {
        for ( i = 0; i < q.length; i++ ) {
                if ( q.charAt(i) != " " ) {
                        return true
                }
        }
        return false
}

function Completo() {

        if( vacio(document.form4.Medico.value)  == false ) {
                alert("Son necesarios los datos de cliente y/o medico, para generar la orden de estudio")
                return false
        } else {
				if( vacio(document.form3.Cliente.value)  == false ) {
            	    alert("Son necesarios los datos de cliente y/o medico, para generar la orden de estudio")
                	return false
		        } else {
 					if(document.form3.Cliente.value=="0"){
							alert("Son necesarios los datos de cliente y/o medico, para generar la orden de estudio")
                			return false
					} else {
	        	        return true
					}
		        }
        }

}

function Msjadmvo($mens){
	if( $mens == ' ' ) {
		return true
	}else{
		alert($mens)
		return true
	}
}

function Valido() {   
//    if(document.form4.Abono.value > <?php echo $He[0]; ?>){
    if(document.form4.Abono.value > <?php echo $He[0]; ?>){
        alert("Revise la Cantidad a Abonar")   
        return false 
    } else {
        if(document.form4.Abono.value < 0){
            alert("Revise la Cantidad a Abonar")   
            return false 
        } else {
            return true   
        }
    }
}


function Ventana(url){
   window.open(url,"venord","width=750,height=500,left=40,top=50,scrollbars=yes,location=no,dependent=yes,resizable=yes")
}

</script>

<?php

$OtA  = mysql_query("SELECT * FROM otnvas WHERE venta='$Vta' and usr='$Usr'");
$Ot   = mysql_fetch_array($OtA);

$InsA = mysql_query("SELECT institucion,nombre,alias,descuento,condiciones FROM inst where status='ACTIVO' order by institucion");
$Ins  = mysql_fetch_array($InsA);


$MedA = mysql_query("SELECT medico,nombrec,clasificacion,mail FROM med WHERE medico='$Ot[medico]'");
$Med  = mysql_fetch_array($MedA);

$CliA = mysql_query("SELECT nombrec,programa,observaciones,colonia,municipio,numveces,mail FROM cli WHERE cliente='$Ot[cliente]'");
$Cli  = mysql_fetch_array($CliA);

$OtB = mysql_query("SELECT * FROM otdnvas WHERE usr='$Usr'", $link);

while ($Ot2 = mysql_fetch_array($OtB)){
	if ($Ot2[venta]=='1'){
		$venta1='1';
	}elseif($Ot2[venta]=='2'){
		$venta2='1';
	}elseif($Ot2[venta]=='3'){
		$venta3='1';
	}elseif($Ot2[venta]=='4'){
		$venta4='1';
	}elseif($Ot2[venta]=='5'){
		$venta5='1';
	}
}		
		

echo "<table width='100%' cellpadding=0 cellspacing=0 border='0'>";

echo "<tr height='36'><td background='menu/left_cap.gif' width='5'>&nbsp;</td>";

echo "<td background='menu/center_tile.gif' style='vertical-align:top;'>$Gfont ";

echo "<font color='#000099'><b> V E N T A S <b>";

if($_REQUEST[Vta]=='1'){

	echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/ones.png'></a> &nbsp; &nbsp; ";
	
	if($venta2=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two.png'></a> &nbsp; &nbsp; ";
	}
	
	if($venta3=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three.png'></a> &nbsp; &nbsp; ";
	}

	if($venta4=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four.png'></a> &nbsp; &nbsp; ";
	}

	if($venta5=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five.png'></a> &nbsp; &nbsp; ";
	}
	
}elseif($_REQUEST[Vta]=='2'){
	
	if($venta1=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one.png'></a> &nbsp; &nbsp; ";
	}
	
	echo "<a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/twos.png'></a> &nbsp; &nbsp; ";
	
	if($venta3=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three.png'></a> &nbsp; &nbsp; ";
	}

	if($venta4=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four.png'></a> &nbsp; &nbsp; ";
	}

	if($venta5=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five.png'></a> &nbsp; &nbsp; ";
	}

}elseif($_REQUEST[Vta]=='3'){
	
	if($venta1=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one.png'></a> &nbsp; &nbsp; ";
	}
	
	if($venta2=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two.png'></a> &nbsp; &nbsp; ";
	}
	
	echo "<a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/threes.png'></a> &nbsp; &nbsp; ";
	
	if($venta4=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four.png'></a> &nbsp; &nbsp; ";
	}

	if($venta5=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five.png'></a> &nbsp; &nbsp; ";
	}

}elseif($_REQUEST[Vta]=='4'){

	if($venta1=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one.png'></a> &nbsp; &nbsp; ";
	}
	
	if($venta2=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two.png'></a> &nbsp; &nbsp; ";
	}

	if($venta3=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three.png'></a> &nbsp; &nbsp; ";
	}

	echo "<a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/fours.png'></a> &nbsp; &nbsp; ";
	
	if($venta5=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/five.png'></a> &nbsp; &nbsp; ";
	}

}elseif($_REQUEST[Vta]=='5'){

	if($venta1=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=1'><img src='images/one.png'></a> &nbsp; &nbsp; ";
	}
	
	if($venta2=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=2'><img src='images/two.png'></a> &nbsp; &nbsp; ";
	}
	
	if($venta3=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=3'><img src='images/three.png'></a> &nbsp; &nbsp; ";
	}

	if($venta4=='1'){echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four2.png'></a> &nbsp; &nbsp; ";
	}else{echo "&nbsp; <a class='vt' href='ordenesnvasN.php?Vta=4'><img src='images/four.png'></a> &nbsp; &nbsp; ";
	}

	echo "<a class='vt' href='ordenesnvasN.php?Vta=5'><img src='images/fives.png'></a> &nbsp; &nbsp; ";
	
}

echo "<a class='vt' href='ordenesnvasN.php?Vta=$Vta&Usr=$Usr&op=br&bt=si'>[Borra/todo]</a> &nbsp; ";

echo "</td>";

echo "<td background='menu/center_tile.gif' align='right'>$Gfont <a class='pg' href=javascript:winuni2('ordenrecest.php')><img src='lib/hora.png' border='0' width='22'> </a> &nbsp; ";

echo "<img src='lib/msjn.png' border='0'><font color='#ffffff'> $Usr ";
echo " | Sin leer <a class='vt' href=javascript:winuni('msjrec.php')> $nMsj <font color='#69b747'>  mensaje(s) </font></a>";
echo " | <a class='vt' href=javascript:winuni('msjenve.php?busca=NUEVO')> Nvo.mensaje</a> | ";
echo " <a class='vt' href='logout.php'> Salir</a><img src='lib/exit.png' border='0'> &nbsp; ";
echo "</td>";
echo "<td background='menu/right_cap.gif' width='5'>&nbsp;</td>";
echo "</tr></table>";






echo "<table width='100%' cellpadding=0 cellspacing=0 border='0'>";

echo "<tr height='36'><td align='center' width='80%'></td>";

//echo "<td background='menu/center_tile.gif' style='vertical-align:top;'>$Gfont </td>";

if($Ot[idprocedencia]=='ambu'){
  $alerta='Yellow';
}else{
  $alerta='white';
}

echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=ambu'><img src='images/ambu.png' onmouseover='this.width=35;this.height=35;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";

if($Ot[idprocedencia]=='silla'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=silla'><img src='images/silla.png' onmouseover='this.width=35;this.height=35;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";

if($Ot[idprocedencia]=='terceraedad'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=terceraedad'><img src='images/terceraedad.png' onmouseover='this.width=35;this.height=35;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";

if($Ot[idprocedencia]=='problemasv'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=problemasv'><img src='images/problemasv.png' onmouseover='this.width=35;this.height=35;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";


if($Ot[idprocedencia]=='bebe'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=bebe'><img src='images/bebe.png' onmouseover='this.width=35;this.height=35;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";

echo "</tr></table>";


//************** INSTITUCION  ************************

echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='0'>";

?>

<script language="JavaScript1.2">

  $(function() {

    <?php
    $con = "select inst.institucion, inst.nombre, inst.status from inst where inst.status='ACTIVO'";//consulta para seleccionar las palabras a buscar, esto va a depender de su base de datos
      $query = mysql_query($con);
      while($row= mysql_fetch_array($query)) {//se reciben los valores y se almacenan en un arreglo
        $resultado3 = str_replace("&nbsp;", " ",$row['nombre']);
        $elementosi[]= '"'.$row['institucion']  ." - ".  $resultado3.'"';
        //$elementos[]= '"'.$row['estudio'].' - '.$row['descripcion'].'"';
    
      }

      $arregloi= implode(", ", $elementosi);//junta los valores del array en una sola cadena de texto
    ?>  
    
   var availableTags=new Array(<?php echo $arregloi; ?>);//imprime el arreglo dentro de un array de javascript
   
        
    $( "#tagsi" ).autocomplete({
      source: availableTags
    });

  });

</script>
<?php

echo "<tr bgcolor='#eff2f9'  valign='middle'>";
echo "<td width='5%' align='left'>";
echo "$Gfont <font size='2'> Institucion: </font>";
echo "</td>";
echo "<td width='10%' valign='middle'>";

echo "<form name='form1' method='post' action='ordenesnvasN.php?op=pr'>";
echo "<label for='tagsi'></label><input id='tagsi' type='text' name='Institucion' size='30' maxlength='30' placeholder='Clave o Nombre'>";
echo "<input type='hidden' name='Vta' value=$Vta>"; 

echo "</td>";

echo "<td width='35%' colspan=2 valign=middle><div>";
echo "<a href=javascript:winuni('instobs.php?busca=$Ot[inst]')><img src='lib/int4.gif' border='0' width='20' height='20' align='middle'></a>";
$InsA = mysql_query("SELECT institucion,nombre,alias,descuento,condiciones FROM inst where institucion='$Ot[inst]'");
$Ins  = mysql_fetch_array($InsA);
echo "$Gfont<font color='#000099' size=1><b>$Ot[inst] - $Ins[nombre]</b></font>$Gfont&nbsp; Lista: $Ot[lista]";
$InsC = mysql_query("SELECT condiciones,mail,msjadministrativo FROM inst where institucion=$Ot[inst]");
$Ins2=mysql_fetch_array($InsC);
if ($Ins2[condiciones]=='CONTADO'){
	echo "$Gfont<font color='#000099' size=-1> &nbsp; $Ins2[condiciones]";
}else{
	echo "$Gfont<font color='#CC0000' size=-1> &nbsp; $Ins2[condiciones]";
}
echo "</font>$Gfont &nbsp; $Ins2[mail]";
echo "</form>";
echo "</td>";

echo "<td width='15%' align='center' colspan='2'>";
echo "<form name='form8' method='post' action='ordenesnvasN.php?op=Sv&Vta=$Vta'>";
if($Ot[servicio]==""){$DisSer="Ordinaria";}else{$DisSer=$Ot[servicio];}
echo "$Gfont Servicio: ";
echo "<select size='1' name='Servicio' class='Estilo10' onchange=this.form.submit()>";
echo "<option value='Ordinaria'>Ordinaria</option>";
echo "<option value='Urgente'>Urgente</option>";
echo "<option value='Express'>Express</option>";
echo "<option value='Hospitalizado'>Hospitalizado</option>";
echo "<option value='Nocturno'>Nocturno</option>";
echo "<option selected value='$DisSer'>$DisSer</option>";
echo "</select>";
echo "</form>";
/*
if($Ot[idprocedencia]=='ambu'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' width='3%' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=ambu'><img src='images/ambu.png' onmouseover='this.width=40;this.height=40;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";
*/
echo "</td></div>";	
echo "</tr>";
  
  //************** PACIENTE  ************************

echo "<td align='left'>";
echo "$Gfont Paciente: ";
echo "</td>";
echo "<td>";
echo "<form name='form3' method='post' action='ordenesnvasN.php?op=cl&Vta=$Vta'>";
//echo "<form name='form3' method='post' action='clientesordN.php?Vta=$_REQUEST[Vta]&Vta=$Vta&pagina=1'>";
$nPrg=$Cli[programa];
echo "<input class='textos' name='Cliente' type='text' value='$Ot[cliente]' size='30' maxlength='30'>";
echo " &nbsp; <a href='clientesordN.php?Vta=$_REQUEST[Vta]&busca=ini'><img src='images/lupa.png' border='0' align='middle'></a>";

echo "</td>";
echo "<td colspan='4'>";

if($Cli[observaciones]<>''){
	echo "<a href=javascript:winuni('cliobs.php?busca=$Ot[cliente]')><img src='lib/int4.gif' border='0' width='20' height='20' align='middle'></a>";
}
echo "<a class='vt' href=javascript:winuni('Upcliente.php?busca=$Ot[cliente]') title='Click para actualizar sus datos'>$Gfont<font color='#000099' size=1><b>".strtoupper(substr($Cli[nombrec],0,35))."</b></a>";
echo "</font> / Col.".ucwords(strtolower($Cli[colonia])) . " " . ucwords(strtolower($Cli[municipio]));
echo "</font>$Gfont &nbsp; E-mail: $Cli[mail] ";
echo " &nbsp; Programa: $aPrg[$nPrg] &nbsp;";
echo "$Gfont vecs: <b><a class='vt' href=javascript:winuni('repots.php?busca=$Ot[cliente]')><font color='#FF0000' size=+1>".$Cli[numveces]."</b></a>";
echo "</font></form>";
echo "</td>";
/*
if($Ot[idprocedencia] #c0392b=='silla'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=silla'><img src='images/silla.png' onmouseover='this.width=40;this.height=40;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";
*/

//************** MEDICO  ************************

echo "<tr bgcolor='#eff2f9'>";
echo "<td>";
echo "$Gfont Medico: ";
echo "</td>";

?>

<script language="JavaScript1.2">

  $(function() {

    <?php
    $con = "select med.medico, med.nombrec, med.status from med where med.status='Activo'";//consulta para seleccionar las palabras a buscar, esto va a depender de su base de datos
      $query = mysql_query($con);
      while($row= mysql_fetch_array($query)) {//se reciben los valores y se almacenan en un arreglo
        $resultado2 = str_replace("&nbsp;", " ",$row['nombrec']);
        $elementos[]= '"'.$row['medico']  ." - ".  $resultado2.'"';
        //$elementos[]= '"'.$row['estudio'].' - '.$row['descripcion'].'"';
    
      }

      $arreglo= implode(", ", $elementos);//junta los valores del array en una sola cadena de texto
    ?>  
    
   var availableTags=new Array(<?php echo $arreglo; ?>);//imprime el arreglo dentro de un array de javascript
   
        
    $( "#tags" ).autocomplete({
      source: availableTags
    });

  });

</script>
<?php
echo "<td>";
echo "<form name='form4' method='post' action='ordenesnvasN.php?Vta=$Vta&op=md'>";

echo "<label for='tags'></label><div><input id='tags' type='text' name='Medico' size='30' maxlength='30' placeholder='Clave o Nombre'>";

echo " &nbsp; <a href='medicosN.php?orden=med.medico&filtro=*&filtro3=*&filtro5=*&filtro7=*&filtro9=*&Vta=$Vta'><img src='images/lupa.png' alt='Catalogo de medicos' border='0' align='middle'></a> &nbsp;";
echo "</td>";
echo "<td colspan=2>";
echo "<a href=javascript:winuni('medicobs.php?orden=$Ot[medico]')><img src='lib/int4.gif' border='0' width='20' height='20' align='middle'></a>";
$Medico2 = $Ot[medico];
if($Medico2=='MD'){$nommed=$Ot[medicon];}else{$nommed=substr($Med[nombrec],0,45);}
echo "$Gfont<font color='#000099' size=1><b>".$Med[medico]." - ".$nommed;
echo "</b></font> &nbsp; E-mail: $Med[mail]";
echo " &nbsp; Clasif.: <font color=#FF0000 size=+1> $Med[clasificacion] &nbsp; </font>";
if($Med[clasificacion]<>''){
	echo "<a href=javascript:winuni('medobs.php')><img src='lib/analiza.gif' border='0' height='25'></a>";
}
echo "</form>";
if($Medico=='MD'){
	echo "<form name='form4' method='post' action='ordenesnvasN.php?Vta=$Vta&op=med'>";
	echo "<input name='Medicon' type='text' size='30' value='MEDICO DIVERSO'>";
	echo "</form>";
}
echo "</td>";

echo "<td align='center' colspan='2'>";

echo "<form name='form4' method='post' action='ordenesnvasN.php?op=rec&Vta=$Vta'><font size='-1'>$Gfont";
echo "$Gfont No.de receta o folio alterno: ";
echo "<input class='textos' name='Receta' type='text' size='10' value ='$Ot[receta]'>";
echo "</form>"; 
echo "</td>";
/*
if($Ot[idprocedencia]=='terceraedad'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=terceraedad'><img src='images/terceraedad.png' onmouseover='this.width=40;this.height=40;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";
*/
echo "</tr>";

//************** ESTUDIO  ************************

?>

<script language="JavaScript1.2">

  $(function() {

    <?php

    $OtnvaA   = mysql_query("SELECT lista,fechae,servicio,inst FROM otnvas WHERE usr='$Usr' and venta='$Vta'");

    $Otnva = mysql_fetch_array($OtnvaA);

    $Lista = "lt".ltrim($Otnva[lista]);



    $cone = "select est.estudio, est.descripcion, est.activo, $Lista as preciosl from est where est.activo='Si'";//consulta para seleccionar las palabras a buscar, esto va a depender de su base de datos
      $querye = mysql_query($cone);
      while($rowe= mysql_fetch_array($querye)) {//se reciben los valores y se almacenan en un arreglo
        $resultado2e = str_replace("&nbsp;", " ",$rowe['descripcion']);
        $resultado3e = str_replace("&nbsp;", " ",$rowe['preciosl']);
        $elementose[]= '"'.$rowe['estudio']  ." - ". $resultado2e." $ ". $resultado3e.'"';

        //$elementos[]= '"'.$row['estudio'].' - '.$row['descripcion'].'"';
    
      }

      $arregloe= implode(", ", $elementose);//junta los valores del array en una sola cadena de texto
    ?>  
    
    var availableTagse=new Array(<?php echo $arregloe; ?>);//imprime el arreglo dentro de un array de javascript
        
    $( "#tagse" ).autocomplete({
      source: availableTagse
    });
  });

</script>
<?php

echo "<tr>";
echo "<td>";
echo "$Gfont Estudio: ";
echo "</td>";

echo "<td>";
echo "<form name='form2' method='post' action='ordenesnvasN.php?op=Estudio&Vta=$Vta'> ";
//echo "<input class='textos' name='Estudio' type='text' size='10' > &nbsp; ";
echo "<label for='tagse'></label><div><input id='tagse' type='text' name='Estudio' size='30' maxlength='30' placeholder='Clave o Nombre' autofocus='autofocus'>";

echo " &nbsp; <a href='agrestordN.php?Vta=$Vta&Usr=$Usr&orden=est.descripcion&Inst=$Ot[inst]'><img src='images/lupa.png' alt='Busca en el catalogo de estudios' border='0'></a>";

echo "</form>"; 
echo "</td>";

//*************** contenido *********************//

echo "<td align='center'>";
echo "<form name='form4' method='post' action='ordenesnvasN.php?op=conte&Vta=$Vta&Inst=$Ot[inst]'>";
echo "$Gfont Contenido Est:<input class='textos' name='Contenido' type='text' size='35' placeholder='Estudio'>";
echo "</form>";
echo "</td>";

//*************** Dato *********************//


echo "<td align='center'>";
echo "<form name='form4' method='post' action='ordenesnvasN.php?op=dato&Vta=$Vta'>";
echo "$Gfont Dato complementario:<input class='textos' name='Datoc' type='text' size='35' value ='$Ot[datoc]'>";
echo "</form>";
echo "</td>";

echo "<td>";
echo "<form name='form4' method='post' action='ordenesnvasN.php?op=Fc&Vta=$Vta'>";
echo "$Gfont Fech/entrega:<input class='textos' type='text' name='Fechae' value ='$Ot[fechae]' maxlength='10' size='12'>";
echo "</form>";
echo "</td>";
echo "<td align='center'>";
echo "<form name='form4' method='post' action='ordenesnvasN.php?op=Fc1&Vta=$Vta'>";
echo "<input class='textos' type='text' name='Horae' value ='$Ot[horae]' maxlength='8' size='6'>";
echo "</form>";

echo "</td>";
/*
if($Ot[idprocedencia]=='problemasv'){
  $alerta='Yellow';
}else{
  $alerta='white';
}
echo "<td align='center' bgcolor='$alerta'><a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=problemasv'><img src='images/problemasv.png' onmouseover='this.width=40;this.height=40;' onmouseout='this.width=30;this.height=30;' width='30' height='30' alt='' /></a></td>";
*/

echo "</tr>";
echo "</table>";
echo "<hr>";

//***********  ENTREGA SUCURSALES ********

if($Ot[tentregamost]==2){
  $Matriz="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=1&tentregamost=1'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=2&tentregamost=0'><img src='lib/slc.png' width='15'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=3&tentregamost=3'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=4&tentregamost=4'>Los Reyes</a>";
}elseif($Ot[tentregamost]==3){
  $Matriz="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=1&tentregamost=1'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=2&tentregamost=2'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=3&tentregamost=0'><img src='lib/slc.png' width='15'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=4&tentregamost=4'>Los Reyes</a>";
}elseif($Ot[tentregamost]==4){
  $Matriz="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=1&tentregamost=1'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=2&tentregamost=2'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=3&tentregamost=3'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=4&tentregamost=0'><img src='lib/slc.png' width='15'>Los Reyes</a>";
}elseif($Ot[tentregamost]==1){
  $Matriz="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=1&tentregamost=0'><img src='lib/slc.png' width='15'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=2&tentregamost=2'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=3&tentregamost=3'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&Sucent=4'>Los Reyes</a>";
}elseif($Ot[tentregamost]==0){
  $Matriz="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=1&tentregamost=1'>Matriz</a>";
  $Futura="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=2&tentregamost=2'>H.Futura</a>";
  $Tepexpan="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=3&tentregamost=3'>Tepexpan</a>";
  $Reyes="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=most&Sucent=4&tentregamost=4'>Los Reyes</a>";
}

//***********  ENTREGA DOMICILIO  ********

if($Ot[tentregamed]==1){
  $Particular="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=0'><img src='lib/slc.png' width='15'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=2'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=3'>Institucion</a>";
}elseif($Ot[tentregamed]==2){
  $Particular="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=1'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=0'><img src='lib/slc.png' width='15'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=3'>Institucion</a>";
}elseif($Ot[tentregamed]==3){
  $Particular="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=1'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=2'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=0'><img src='lib/slc.png' width='15'>Institucion</a>";
}else{
  $Particular="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=1'>Paciente</a>";
  $Dmedico="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=2'>Medico</a>";
  $Dinstitucion="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=medi&tentregamed=3'>Institucion</a>";
}

//***********  ENTREGA DIGITAL  ********

if($Ot[entemailpac]==1){
  $seleccionmp="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=0&entemailpac=0&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]'><img src='lib/slc.png' width='15'>";
}else{
  $seleccionmp="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=1&entemailpac=1&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]'>";
}

if($Ot[entemailmed]==1){
  $seleccionmm="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=0&entemailpac=$Ot[entemailpac]&entemailmed=0&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]'><img src='lib/slc.png' width='15'>";
}else{
  $seleccionmm="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=3&entemailpac=$Ot[entemailpac]&entemailmed=1&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]'>";
}

if($Ot[entemailinst]==1){
  $seleccionmi="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=0&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=0&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]'><img src='lib/slc.png' width='15'>";
}else{
  $seleccionmi="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=5&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=1&entwhatpac=$Ot[entwhatpac]&entwhatmed=$Ot[entwhatmed]'>";
}

if($Ot[entwhatpac]==1){
    $seleccionwp="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=2&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=0&entwhatmed=$Ot[entwhatmed]'><img src='lib/slc.png' width='15'>";
}else{
    $seleccionwp="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=2&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=1&entwhatmed=$Ot[entwhatmed]'>";
}

if($Ot[entwhatmed]==1){
    $seleccionwm="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=4&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=0'><img src='lib/slc.png' width='15'>";
}else{
    $seleccionwm="<a class='vt' href='ordenesnvasN.php?Vta=$Vta&op=digi&tentregadig=4&entemailpac=$Ot[entemailpac]&entemailmed=$Ot[entemailmed]&entemailinst=$Ot[entemailinst]&entwhatpac=$Ot[entwhatpac]&entwhatmed=1'>";
}

  $Emailpac=$seleccionmp."Mail/Pac</a>";
  $Whatpac=$seleccionwp."Whats/Pac</a>";
  $Emailmed=$seleccionmm."Mail/Med</a>";
  $Whatmed=$seleccionwm."Whats/Med</a>";
  $Emailinst=$seleccionmi."Mail/Inst</a>";

//***********  TIPO DE ENTREGA  ********

echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'>";

echo "<tr>";

echo "<td align='left' width='7%'>$Gfont <b><font size=1>ENTREGA --></b></td>";

echo "<td align='left' width='31%'>$Gfont <font color=#17202a size=1><u>MOSTRADOR:</u> &nbsp; &nbsp; ".$Matriz."   &nbsp; &nbsp; ".$Futura."  &nbsp; &nbsp; ".$Tepexpan."   &nbsp; &nbsp; ".$Reyes."</td>";

echo "<td align='left' width='24%'>$Gfont <font color=#17202a size=1><u>DOMICILIO:</u> &nbsp; &nbsp; ".$Particular."   &nbsp; &nbsp; ".$Dmedico."  &nbsp; &nbsp; ".$Dinstitucion."</td>";

echo "<td align='left' width='38%'>$Gfont <font color=#17202a size=1><u>DIGITAL:</u> &nbsp; &nbsp;".$Emailpac."   &nbsp; &nbsp;".$Whatpac." &nbsp; &nbsp;".$Emailmed." &nbsp; &nbsp;".$Whatmed." &nbsp; &nbsp;".$Emailinst."</td>";

echo "</tr>"; 
echo "</table>";

echo "<hr>";

if(!$res=mysql_query($cSql.$cWhe,$link)){

	cMensaje("No se encontraron resultados � hay un error en el filtro");    #Manda mensaje de datos no existentes

}else{

	CalculaPaginas();        #--------------------Calcual No.paginas-------------------------

	if($limitInf < 0){$limitInf=0;}

	$sql = $cSql.$cWhe." ORDER BY ".$orden." ASC LIMIT ".$limitInf.",".$tamPag;

	$res = mysql_query($sql,$link);

	//PonEncabezado();         #---------------------Encabezado del browse----------------------
  echo "<table align='center' width='100%' border='0' cellpadding='0' cellspacing='1'>";
  echo "<tr align='center'>";
  echo "<td>$Gfont <b>Estudio</b></td>";
  echo "<td>$Gfont <b>Descripcion</b></td>";
  echo "<td>$Gfont <b>Precio</b></td>";
  echo "<td>$Gfont <a class='vt' href='descuentosN.php?Vta=$Vta&op=dtg'><b>Movto</a></b></td>";
  echo "<td>$Gfont <b>Importe</b></td>";
  echo "<td>$Gfont <b>Elim</b></td>";
  echo "<td>$Gfont <b>Fec/Entrega</b></td>";
  echo "</tr>";

	while($registro=mysql_fetch_array($res)){

		if( ($nRng % 2) > 0 ){$Fdo='FFFFFF';}else{$Fdo=$Gfdogrid;}    //El resto de la division;

		echo "<tr bgcolor='$Fdo' onMouseOver=this.style.backgroundColor='$Gbarra';this.style.cursor='hand' onMouseOut=this.style.backgroundColor='$Fdo';>";
		$estud = strtolower($registro[estudio]);            
		echo "<td align='left'><a href=javascript:wingral('resultapdf.php?Estudio=$registro[estudio]&alterno=0')><font size='-1'><img src='pdfenv.png' alt='pdf' border='0' width='15' height='15'/></font></a>$Gfont<a href=javascript:winuni('estudiosobs.php?estudio=$registro[estudio]')>$registro[estudio]</a></td>";
		echo "<td>$Gfont <font size='-2'>$registro[descripcion]</font></td>";
		echo "<td align='right'>$Gfont ".number_format($registro[precio],"2")."</td>";
		echo "<td align='center'>$Gfont <a class='pg' href='descuentosN.php?Vta=$Vta&op=dt&Estudio=$registro[estudio]'>$registro[descuento]</a></td>";
		echo "<td align='right'>$Gfont ".number_format($registro[precio]*(1-$registro[descuento]/100),"2")."</td>";
		echo "<td align='center'>$Gfont <a href='ordenesnvasN.php?op=Eli&busca=$registro[estudio]&Vta=$Vta'><img src='lib/dele.png' alt='Elimina' border='0'></a></td>";
		echo "<td align='center'>$Gfont $registro[fechaest]</td>";
		echo "</tr>";
		$nImp=$nImp+($registro[precio]*(1-($registro[descuento]/100)));
		$nRng++;
	}

	PonPaginacion4(false);      #-------------------pon los No.de paginas-------------------

}//fin if

echo "<table border='0' width='100%' align='center' border=0 cellpadding=0 cellspacing=0>";

echo "<tr>";
echo "<td width='50%' align='left'>";
echo "<table width='100%' border='0'>";
	  
echo "<form name='form4' method='post' action='ordenesnvasN.php?op=do&Vta=$Vta'> ";
echo "<tr>";
echo "<td align='center' valign=top>$Gfont Diagnostico Medico:</td>"; 
echo "<td><TEXTAREA NAME='Diagmedico' cols='60' rows='2'>$Ot[diagmedico]</TEXTAREA></td>";
echo "</tr>";
	  
echo "<tr>";
echo "<td align='center' valign=top>$Gfont Observaciones: <input class='textos' type='submit' name='Submit' value='Enviar'></td>"; 
echo "<td><TEXTAREA NAME='Observaciones' cols='60' rows='2'>$Ot[observaciones]</TEXTAREA>";
echo "</td>";
echo "</tr>"; 
echo "</table></td>";
echo "</form>";

echo "<td width='50%' align='left'>";
echo "<table width='100%' border='0'>";

echo "<tr>";
echo "<td align='left'>$Gfont<b>Importe: $ </td>";
echo "<td align='left'>$Gfont".number_format($He[0],'2')."</b></td>";
echo "<td align='center'>$Gfont<b>T/Pago:</td>";
echo "<td align='center'>$Gfont<b>Fec.Receta:</td>";
echo "</tr>";

echo "<form name='form4' method='post' action='ordenesnvasN.php?op=ab&Vta=$Vta' onSubmit='return Valido();'>";
echo "<tr>";
echo "<td align='left'>$Gfont<b>Abono: $ </td>";
echo "<td align='left'><input class='textos' name='Abono' type='text' size='10' value ='$Ot[abono]'></td>";
echo "</form>";

echo "<form name='form4' method='post' action='ordenesnvasN.php?op=tp&Vta=$Vta'> ";
if($Ot[tpago]==""){$tpago1="Efectivo";}else{$tpago1=$Ot[tpago];}
echo "<td align='center'>"; 
echo "<select name='Tpago' class='Estilo10' onchange=this.form.submit()>";
echo "<option value='Efectivo'>Efectivo</option>";
echo "<option value='Tarjeta'>Tarjeta</option>";
echo "<option value='Cheque'>Cheque</option>";
echo "<option value='Transferencia'>Transferencia</option>";
echo "<option value='Credito'>Credito</option>";
echo "<option value='Nomina'>Nomina</option>";
echo "<option selected value =$tpago1>$tpago1</option>";
echo "</select>";
echo "</form></td>";
echo "<td align='center'>";
echo "<form name='form4' method='post' action='ordenesnvasN.php?op=frec&Vta=$Vta'>";
echo "$Gfont <input class='textos' name='Fechar' type='text' size='10' value='$Ot[fechar]'>";
echo "</form>";
echo "</td>";
echo "</tr>";

echo "<tr>";
echo "<td align='left'>$Gfont<b>Resta: $ </td>";
echo "<td align='left'>$Gfont".number_format($He[0]-$Ot[abono],'2')."</b></td>";



//******Responsable Economico *************//

echo "<form name='form4' method='post' action='ordenesnvasN.php?op=reseco&Vta=$Vta'>";
echo "<td align='left' colspan='2'>";
echo "$Gfont <b>Resp. Econom: </b><input class='textos' name='Reseco' type='text' size='40' value ='$Ot[responsableco]'>";
echo "</td>";
echo "</form>";


echo "</tr>";

echo "<tr>";

echo "<form name='formNva' method='post' action='ordenesnvasN.php?Vta=$Vta&op=Fn' onSubmit='return Completo();'>";
echo "<td align='center' colspan='2'><input type='submit' name='Genera' value='Genera Orden de Estudios'></td>";
echo "<td align='center'><a class='vt' href=javascript:Ventana('cotizacion.php?Vta=$Vta')>Cotizacion</a></td>";
echo "<td align='center'><a class='vt' href=javascript:Ventana('impos.php')>Servicio a domicilio</a></td>";

echo "</form>";
echo "</td>";
echo "</tr>";

echo "</table></td>";
echo "</tr>";

echo "</td>";
echo "</tr>";

echo "</table>";

echo "</tr>";
echo "</table>";

mysql_close();

echo "</body>";

echo "</html>";

?>
<style type='text/css'>

a.vt:link {

 color: #003c72;

    font-size: 11px;

    text-decoration: none;

}

a.vt:visited {

    color: #003c72;

    font-size: 14px;

    text-decoration: none;

}

a.vt:hover {

    color: #111111;

    font-size: 14px;

}

</style>

