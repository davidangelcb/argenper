<?php

require_once("lib/config.php");
require_once("lib/funcs.php");
/*
 * FUNCTIONS
 */
if (!sessionValid()) {
    echo "Error Session Lost, Please Login Again";
    exit;
}

function listNews($_GET)
{
	//Aqui se ejecuta el SYS_SMS sincronizador en segundo plano
        file_get_contents('http://192.168.100.10/SYS_SMS/controllers/sincronizarController.php');
        // verifica identidad en base de datos
        require_once("lib/load.php");
        $page = $_GET['page']; // get the requested page 
        $limit = $_GET['rows']; // get how many rows we want to have into the grid 
        $sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
        $sord = $_GET['sord']; // get the direction 

        if(!$sidx) $sidx =1;
        
        $query = "SELECT COUNT(*) AS count from  argenper_tblSMS where   tip_entrega = 'O' and argenper_estado ='PP'  and    (estado_envio=0 or estado_envio is null  )";
	$dataTotal = DbArgenper::fetchOne($query);//

        $count = $dataTotal['count']; 
        if( $count >0 ) { 
            $total_pages = ceil($count/$limit); 
        } else { 
            $total_pages = 0; 
        }
        if ($page > $total_pages){ $page=$total_pages; }
        if( $count >0 ) { 
            $start = $limit*$page - $limit; // 
        }else{
            $start = 0; // do not put $limit*($page - 1) 
        }
        $SQL = "SELECT  id_ref as id,  numero_giro,  nombre_cliente,  DATE_FORMAT(fecha_ingreso,'%m-%d-%Y') as 'respuesta_api', celular_cliente,  mensaje_cliente FROM   argenper_tblSMS where    tip_entrega = 'O' and  argenper_estado ='PP'  and    (estado_envio=0 or estado_envio is null  )       ORDER BY $sidx $sord LIMIT $start , $limit"; 
        // Error: Tamaño mensaje(150)
        // Error: Verificacion Celular 
        // Error: Argenper Validacion 

        //$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error()); 
        $giros = DbArgenper::fetchAll($SQL);//fetchAll
        
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count; 
        $i=0;
        foreach($giros as $row){
            $response->rows[$i]['id']=$row['id']; 
            $response->rows[$i]['cell']=array("",$row['numero_giro'],htmlentities($row['nombre_cliente']),$row['respuesta_api'],$row['celular_cliente'],$row['mensaje_cliente']); 
            $i++; 
        }

        return json_encode($response);
}
function edit($_POST){
        require_once("lib/load.php");
        
        $params = array(
            'celular_cliente' => $_POST['celular_cliente'],
            'mensaje_cliente' => $_POST['mensaje_cliente']
        );
        DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $_POST['id']);    
        $response['error'] = 0;
        $response['message'] = 'Se Actualizo la nota';
        return json_encode($response);
}
function procesa($_POST){
        require_once("lib/load.php");
        
        $ids =  $_POST['ids'] ;
        $iduser=1;
        if(isset($_SESSION['UID'])){
            $iduser=$_SESSION['UID'];
        }
        
        $SQL = "SELECT  id_ref as id,  numero_giro, argenper_estado, nombre_cliente,  DATE_FORMAT(fecha_ingreso,'%m-%d-%Y') as 'respuesta_api', celular_cliente,  mensaje_cliente FROM   argenper_tblSMS where id_ref in (".$ids.")"; 
        $giros = DbArgenper::fetchAll($SQL);//fetchAll
        $cantGiros = 0;
        $idsSend="";
        $enviados = 0;
        $pendin = 0;
        $idsFail="";
        foreach ($giros as $giro) {
            $cantGiros++;
            $nx=  strlen($giro['mensaje_cliente']);
            $datel = date("Y-m-d H:i:s");
            
            $tipoMensaje = valTipoPhone($giro['celular_cliente']);
            if($tipoMensaje['value']=='V'){
                $resX = validacionVoice($giro['celular_cliente'],$giro['argenper_estado']);
            }else{
                $resX = validacion($giro['celular_cliente'],$giro['mensaje_cliente'],$giro['argenper_estado']);
            }
            if($resX=='P'){
                        $params = array(
                            'estado_envio'=>4,
                            'fecha_proceso' => $datel,
                            'longitud_sms'=>$nx,
                            'userid'=>$iduser,
                            'respuesta_api'=>$resX,
                            'tipo'=>$tipoMensaje['value']
                        );
                        DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $giro['id']);  
            }else{
                if($resX==''){
                    // send to cron
                    $params = array(
                        'userid'=>$iduser,
                        'estado_envio' => 100,
                        'tipo'=>$tipoMensaje['value']
                    );
                    DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $giro['id']);  
                    $idsSend .= ($idsSend == "") ? $giro['id'] : ",".$giro['id'];
                    $enviados++;
                }else{
                        $params = array(
                            'estado_envio'=>5,
                            'fecha_proceso' => $datel,
                            'longitud_sms'=>$nx,
                            'userid'=>$iduser,
                            'respuesta_api'=>$resX,
                            'argenper_estado'=>'XX',
                            'tipo'=>$tipoMensaje['value']
                        );
                        DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $giro['id']);
                        $idsFail .= ($idsFail == "") ? $giro['id'] : ",".$giro['id'];
                        $pendin++;
                } 
            }
       }
        
        $paramsInsert = array(
            'idlog'=>NULL,
            'fecha'=>$datel,
            'q'=>$cantGiros,
            'ids'=>$_POST['ids'],
            'ids_done' =>$idsSend,
            'ids_fail'=>$idsFail,
            'iduser' =>$iduser,
            'estado'=>'E'

        );
        DbArgenper::insert('argenper_processlog', $paramsInsert);
        
        return "Done||".$enviados;
}

/*
 * HANDLER
 */
if (isset($_REQUEST["oper1"]) && !empty($_REQUEST["oper1"])) {
    if (!get_magic_quotes_gpc()) {
        foreach ($_REQUEST as $key => $value) {
            $_REQUEST[$key] = addslashes($value);
        }
    }
    switch ($_REQUEST["oper1"]) {
        case "listNews_" : echo listNews($_GET); break;
        case "edit" : echo edit($_POST); break;
        case "procesa": echo procesa($_POST); break;
    }
}
?>
