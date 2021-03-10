<?
//  Autentificator
//  Gestión de Usuarios PHP+Mysql+sesiones
//  by Pedro Noves V. (Cluster)
//  clus@hotpop.com
//  v1.0  - 17/04/2002 Versión inicial.
//  v1.01 - 24/04/2002 Solucionado error sintactico en aut_verifica.inc.php.
//  v1.05 - 17/05/2002 Optimización código aut_verifia.inc.php
//  v1.06 - 03/06/2002 Corrección de errores de la versión 1.05 y error con navegadores Netscape
//
// Descripción:
// Gestión de Páginas restringidas a Usuarios, con nivel de acceso
// y gestión de errores en el Login
// + administración de usuarios (altas/bajas/modificaciones)
//
// Licencia GPL con estas extensiones:
// - Uselo con el fin que quiera (personal o lucrativo).
// - Si encuentra el código de utilidad y lo usas, mandeme un mail si lo desea.
// - Si mejora el código o encuentra errores, hagamelo saber el mail indicado.
//
// Instalación y uso del Gestor de usuarios en:
// documentacion.htm
//  ----------------------------------------------------------------------------


// Motor autentificación usuarios.

// Cargar datos conexion y otras variables.
require ("aut_config.inc.php");

// chequear página que lo llama para devolver errores a dicha página.

$url = explode("?",$HTTP_REFERER);
$pag_referida=$url[0];
$redir=$pag_referida;
// chequear si se llama directo al script.
if ($HTTP_REFERER == ""){
die ("Error cod.:1 - Acceso incorrecto!");
exit;
}


// Chequeamos si se está autentificandose un usuario por medio del formulario
if (isset($HTTP_POST_VARS['user']) && isset($HTTP_POST_VARS['pass'])) {

// Conexión base de datos.
// si no se puede conectar a la BD salimos del scrip con error 0 y
// redireccionamos a la pagina de error.
$db_conexion= mysql_connect("$sql_host", "$sql_usuario", "$sql_pass") or die(header ("Location:  $redir?error_login=0"));
mysql_select_db("$sql_db");

// realizamos la consulta a la BD para chequear datos del Usuario.
$usuario_consulta = mysql_query("SELECT ID,usuario,pass,nivel_acceso FROM $sql_tabla WHERE usuario='$user'") or die(header ("Location:  $redir?error_login=1"));

 // miramos el total de resultado de la consulta (si es distinto de 0 es que existe el usuario)
 if (mysql_num_rows($usuario_consulta) != 0) {

    // eliminamos barras invertidas y dobles en sencillas
    $login = stripslashes($HTTP_POST_VARS['user']);
    // encriptamos el password en formato md5 irreversible.
    $password = md5($HTTP_POST_VARS['pass']);

    // almacenamos datos del Usuario en un array para empezar a chequear.
 	$usuario_datos = mysql_fetch_array($usuario_consulta);
  
    // liberamos la memoria usada por la consulta, ya que tenemos estos datos en el Array.
    mysql_free_result($usuario_consulta);
    // cerramos la Base de dtos.
    mysql_close($db_conexion);

    // chequeamos el nombre del usuario otra vez contrastandolo con la BD
    // esta vez sin barras invertidas, etc ...
    // si no es correcto, salimos del script con error 4 y redireccionamos a la
    // página de error.
    if ($login != $usuario_datos['usuario']) {
       	Header ("Location: $redir?error_login=4");
		exit;}

    // si el password no es correcto ..
    // salimos del script con error 3 y redireccinamos hacia la página de error
    if ($password != $usuario_datos['pass']) {
        Header ("Location: $redir?error_login=3");
	    exit;}

    // Paranoia: destruimos las variables login y password usadas
    unset($login);
    unset ($password);

    // En este punto, el usuario ya esta validado.
    // Grabamos los datos del usuario en una sesion.
    
     // incia sessiones
    session_start();
    // le damos un mobre a la sesion (por si quisieramos identificarla)
    session_name($usuarios_sesion);
    // Paranoia: decimos al navegador que no "cachee" esta página.
    session_cache_limiter('nocache,private');
    
    // Asignamos variables de sesión con datos del Usuario para el uso en el
    // resto de páginas autentificadas.

    // definimos $usuarios_id como IDentificador del usuario en nuestra BD de usuarios
    $usuario_id=$usuario_datos['ID'];

    // definimos $usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
    $usuario_nivel=$usuario_datos['nivel_acceso'];
	 
    //definimos $usuario_nivel con el Nivel de acceso del usuario de nuestra BD de usuarios
    $usuario_login =$usuario_datos['usuario'];

    //definimos $usuario_password con el password del usuario de la sesión actual (formato md5 encriptado)
    $usuario_password =$usuario_datos['pass'];

    // registramos las variables en la sesión:
    session_register("usuario_id");
    session_register("usuario_nivel");
    session_register("usuario_login");
	session_register("usuario_password");

    // Hacemos una llamada a si mismo (scritp) para que queden disponibles
    // las variables de session en el array asociado $HTTP_...
    Header ("Location: $PHP_SELF?");
    exit;
    
   } else {
      // si no esta el nombre de usuario en la BD o el password ..
      // se devuelve a pagina q lo llamo con error
      Header ("Location: $redir?error_login=2");
      exit;}
} else {

// -------- Chequear sesión existe -------

// Iniciamos el uso de sesiones
session_start();

// Chequeamos si estan creadas las variables de sesión de identificación del usuario,
// El caso mas comun es el de una vez "matado" la sesion se intenta volver hacia atras
// con el navegador.


if (!isset($HTTP_SESSION_VARS['usuario_login']) && !isset($HTTP_SESSION_VARS['usuario_password'])){
// Borramos la sesion creada por el inicio de session anterior
//die("Esto sale ".$HTTP_SESSION_VARS['usuario_login'].$HTTP_SESSION_VARS['usuario_password']);

session_destroy();

die ("Error cod.: 2 - Acceso incorrecto!");
exit;
}
}
?>