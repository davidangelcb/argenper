<?php
require_once("../lib/config.php");
require_once("../lib/funcs.php");
require_once("../lib/load.php"); 

//captura los ID que necesitan ser actualizado

$SQL_ESTADO_ENVIO_1 = "SELECT   fecha_proceso,id_sms, id,estatus_api FROM 
llamada_contactos where  cron_update=1 and estatus_api in (1,2,3,4) 
and fecha_proceso is not null and now()<DATE_ADD(fecha_proceso, INTERVAL 8 DAY)";
$rows = DbArgenper::fetchALL($SQL_ESTADO_ENVIO_1); //get all status 1
$datel = date("Y-m-d H:i:s");
foreach($rows as $giro){
        $actual_status = $giro['estatus_api'];    
    try{
        $urlDomain = HTTP_SMS.HTTP_DOMAIN;
        $new_statusOld = generateGetMSG(2, $urlDomain,USER_SMS, PASS_SMS, $giro['id_sms']);
        $new_status = trim($new_statusOld);
        //$new_status = trim(get_status($giro['id_sms']));
        if(strlen($new_status)==1){
            if ($actual_status!=$new_status) { //si estados son diferentes actualiza con estado desde API
                $params = array(
                    'estatus_api' => $new_status,
                    'fecha_actualizacion' => $datel
                );
                DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']); 
                //argenper_logcron    id   id_ref   estado_origin  estado_fin   fecha  response_api
                $paramsInsert = array(
                    'id'=>NULL,
                    'id_ref'=>$giro['id_ref'],
                    'estado_origin'=>$actual_status,
                    'estado_fin'=>$new_status,
                    'fecha' =>$datel,
                    'response_api'=>''

                );
                DbArgenper::insert('argenper_logcron', $paramsInsert);            
            }
        }else{
                $paramsInsert = array(
                    'id'=>NULL,
                    'id_ref'=>$giro['id_ref'],
                    'estado_origin'=>$actual_status,
                    'estado_fin'=>'',
                    'fecha' =>$datel,
                    'response_api'=>$new_status

                );
                DbArgenper::insert('argenper_logcron', $paramsInsert);            
        
        }
    }catch (Exception $e) {
            $paramsInsert = array(
                'id'=>NULL,
                'id_ref'=>$giro['id_ref'],
                'estado_origin'=>$actual_status,
                'estado_fin'=>'',
                'fecha' =>$datel,
                'response_api'=>$e->getMessage()

            );
            DbArgenper::insert('argenper_logcron', $paramsInsert);                    
    }    
}

// fin proceso

?>