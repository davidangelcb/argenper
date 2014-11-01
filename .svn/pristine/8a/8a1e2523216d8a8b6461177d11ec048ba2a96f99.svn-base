<?php
require_once("../lib/config.php");
require_once("../lib/funcs.php");
require_once("../lib/load.php"); 

//captura los ID que necesitan ser actualizado

$SQL_ESTADO_ENVIO_1 = "SELECT   fecha_proceso,id_ref, id_sms,estado_envio,tipo FROM argenper_tblSMS where  cron_update=0 and estado_envio in (1) and fecha_proceso is not null and now()<DATE_ADD(fecha_proceso, INTERVAL 3 DAY)";
$rows = DbArgenper::fetchALL($SQL_ESTADO_ENVIO_1); //get all status 1
$datel = date("Y-m-d H:i:s");
foreach($rows as $giro){
        $actual_status = $giro['estado_envio'];    
    try{

        $urlDomain = HTTP_SMS.HTTP_DOMAIN;
        if($giro['tipo']=='V'){
            
            $new_statusOld = generatePostMSG(4, $urlDomain,USER_SMS, PASS_SMS, $giro['id_sms']);
        }else{
            $new_statusOld = generatePostMSG(3, $urlDomain,USER_SMS, PASS_SMS, $giro['id_sms']);
        }
        $new_status = trim($new_statusOld);
        
        if($giro['tipo']=='V'){
            if($new_status=='completed'){
                $params = array(
                     'estado_envio' => '4',
                     'fecha_actualizacion_estado' => $datel,
                     'cron_update'=>1
                 );
                 DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $giro['id_ref']); 
                 //argenper_logcron    id   id_ref   estado_origin  estado_fin   fecha  response_api
                 $paramsInsert = array(
                     'id'=>NULL,
                     'id_ref'=>$giro['id_ref'],
                     'estado_origin'=>'1',
                     'estado_fin'=>'4',
                     'fecha' =>$datel,
                     'response_api'=>''

                 );
                 DbArgenper::insert('argenper_logcron', $paramsInsert);            
            }else{
                $msg ="Error Conexion";
                if($new_status=='no-answer'){
                    $msg ="estado llamada sin respuesta";
                }elseif($new_status=='busy'){
                    $msg ="estado llamada ocupado";
                }
                
                    $params = array(
                         //'estado_envio'=>5,
                         'estado_envio'=>0,                             
                         'respuesta_api'=>$msg,
                         'argenper_estado'=>'PP'
                     );
                     DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $giro['id_ref']);   
                     
                     $paramsInsert = array(
                        'id'=>NULL,
                        'id_ref'=>$giro['id_ref'],
                        'estado_origin'=>1,
                        'estado_fin'=>'',
                        'fecha' =>$datel,
                        'response_api'=>$new_status

                    );
                    DbArgenper::insert('argenper_logcron', $paramsInsert); 
               }

        }else{
            if(strlen($new_status)==1){
                if($new_status==2){
                    //no hacemos nada y solo se buscara cada 5 minutos hasta q pase a un nuevo estado

                }else{
                    if ($actual_status!=$new_status) { //si estados son diferentes actualiza con estado desde API
                        $params = array(
                            'estado_envio' => $new_status,
                            'fecha_actualizacion_estado' => $datel,
                            'cron_update'=>1
                        );
                        DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $giro['id_ref']); 
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