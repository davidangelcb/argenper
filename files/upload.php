<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
/*
 * FUNCTIONS
 */

if (isset($_POST['submit'])) {

    if (is_uploaded_file($_FILES['filename']['tmp_name'])) {

        echo "<h1>" . "File " . $_FILES['filename']['name'] . " uploaded successfully." . "</h1>";

        echo "<h2>Displaying contents:</h2>";

        readfile($_FILES['filename']['tmp_name']);
    }
    //Import uploaded file to Database
    $handle = fopen($_FILES['filename']['tmp_name'], "r");
    $directorio = $_POST['directorioActive'];
    $ns=0;
    $agregados = 0;
    $fallos = 0;
    while (($data = fgetcsv($handle, 1000, ",")) !== FALSE) {
        if($data[0]!='Nombre'){
            $datel = date("Y-m-d H:i:s");
             $text = validacion($data[1],'Demo','AS');
            if($text!=''){
                $fallos++;
            }else{
         
                $params = array(
                     "nombre"=> $data[0],
                     "celular"=> $data[1],
                     "valor1"=> $data[2],
                     "valor2"=> $data[3],
                     "email"=>$data[4],
                     "fecha_actualizacion"=>$datel,
                     "id_directorio"=>$directorio
                 );//nombre, celular, valor1,valor2,email
                 $id = DbArgenper::insert("contactos", $params);
                 $ns++;
            }
        }
    }
    $msg = "";
    if($ns>0){
        $msg = "Se Insertaron (".$ns.") Registros";
    }
    if($fallos>0){
        if($msg==''){
            $msg = "Fallaron (".$fallos.") Registros";
        }else{
            $msg.= " y Fallaron (".$fallos.") Registros [verificar celular]";
        }
    }
    $queryUpdate= "update directorio set miembros=miembros+".$ns." where id=".$directorio;
    DbArgenper::exec($queryUpdate);
    
    fclose($handle);
    echo '<script>alert("'.$msg.'");parent.closeWindowActive();</script>';
}            