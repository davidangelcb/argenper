<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
/*
 * FUNCTIONS
 */

if(!isset($_POST['directorio'])){
    echo "Acceso denegado";
    exit;
}
$directorio = $_POST['directorio'];
$directorioSQL = "select * from directorio where id= ?";
$direct  = DbArgenper::fetchOne($directorioSQL, $directorio);
$criterio = $direct['nombre'];
$xdat = "Nombre,Celular, Valor Predeterminado 1, Valor Predeterminado 2, Email \n";
$SQL = "select  nombre, celular, valor1,valor2,email from contactos where id_directorio=? and estatus='E'"; 
$giros = DbArgenper::fetchAll($SQL,$directorio);//fetchAll
foreach ($giros as $row) {
    $row['nombre'] = str_replace(',', '-', $row['nombre']);
    $row['email'] = str_replace(',', '-', $row['email']);
    $row['valor1'] = str_replace(',', '-', $row['valor1']);
    $row['valor2'] = str_replace(',', '-', $row['valor2']);
    
    $xdat.= $row['nombre'] . "," . $row['celular'] . "," . $row['valor1']  . "," . $row['valor2'] . "," . $row['email']."\n";
}
       
$xdatx = stripslashes($xdat);

header("Content-type: application/octet-stream");
header("Content-Disposition: attachment; filename=\"grupo_".$criterio.".csv\"");
echo $xdatx;
?>