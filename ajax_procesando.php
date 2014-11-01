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
function estado($nu){
    $msg="Envio EE";
    switch((int)$nu){
        case 1: $msg= "Procesado"; break;
        case 2: $msg= "Pendiente"; break;
        case 3: $msg= "Fallo"; break;
        case 4: $msg= "Enviado"; break;
        case 100: $msg= "Procesando"; break;
        case 101: $msg= "Bloqueado"; break;
    }
    return $msg;
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
function listPro($_GET)
{
        // verifica identidad en base de datos
        require_once("lib/load.php");
        $page = $_GET['page']; // get the requested page 
        $limit = $_GET['rows']; // get how many rows we want to have into the grid 
        $sidx = $_GET['sidx']; // get index row - i.e. user click to sort 
        $sord = $_GET['sord']; // get the direction 

        if(!$sidx) $sidx =1;
        $queryUser = "select iduser,usuario from argenper_users";
	$users = DbArgenper::fetchAll($queryUser);//
        $nameUser = array();
        foreach ($users as $user) {
            $nameUser[$user['iduser']] =$user['usuario'];
        }
        if(isset($_GET["cd_mask"])) 
            $nm_mask = $_GET['cd_mask']; 
        else 
            $nm_mask = "";
        
        $filtro = ""; 
        if($nm_mask!=''){ 
            $filtro.= " AND numero_giro LIKE '".$nm_mask."%'";
        }
        $query = "SELECT COUNT(*) AS count from  argenper_tblSMS where argenper_estado = 'EE' ".$filtro." or fecha_entrega is not null  and  estado_envio in (100,1,2,3,4) and id_sms is not null".$filtro;
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
        
        $SQL = "SELECT  numero_envios, mensaje_cliente, id_ref as id,  numero_giro,estado_envio,  nombre_cliente,  celular_cliente, DATE_FORMAT(fecha_ingreso,'%m-%d-%Y %T:%S') as 'fecha_ingreso', DATE_FORMAT(fecha_proceso,'%m-%d-%Y %T:%S') as 'fecha_proceso', estado_envio , userid FROM   argenper_tblSMS where  argenper_estado = 'EE' ".$filtro."  or fecha_entrega is not null  and  estado_envio in (100,1,2,3,4) and id_sms is not null ".$filtro."  ORDER BY $sidx $sord LIMIT $start , $limit"; 
        
        //$result = mysql_query( $SQL ) or die("Couldn t execute query.".mysql_error()); 
        $giros = DbArgenper::fetchAll($SQL);//fetchAll
        
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count; 
        $i=0;
        foreach($giros as $row){
            $estado = estado($row['estado_envio']);
            $response->rows[$i]['id']=$row['id']; 
            $response->rows[$i]['cell']=array('',$row['numero_giro'],  htmlentities($row['nombre_cliente']),$row['celular_cliente'],$row['mensaje_cliente'],$row['fecha_proceso'],$estado,$row['numero_envios']); 
            $i++; 
        }

        return json_encode($response);
}
function procesa($_POST){
        require_once("lib/load.php");
        
        $ids =  $_POST['ids'] ;
        $iduser=1;
        if(isset($_SESSION['UID'])){
            $iduser=$_SESSION['UID'];
        }
        
        $SQL = "SELECT  id_ref as id,  numero_giro, argenper_estado, nombre_cliente,  DATE_FORMAT(fecha_ingreso,'%m-%d-%Y') as 'respuesta_api', celular_cliente,  mensaje_cliente FROM   argenper_tblSMS WHERE estado_envio in (3,4) and id_ref in (".$ids.")"; 
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
                $resX = validacionTwo($giro['celular_cliente'],$giro['mensaje_cliente'],$giro['argenper_estado']);
            }
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
        case "listPro_" : echo listPro($_GET); break;
        case "edit" : echo edit($_POST); break;
        case "procesa": echo procesa($_POST); break;
    }
}
?>