<?php
require_once("../lib/config.php");
require_once("../lib/funcs.php");
require_once("../lib/load.php"); 

//captura los ID que necesitan ser actualizado

$SQL_ESTADO_ENVIO_1 = "SELECT  cron_intentos, fecha_proceso,id_ref, id_sms,estado_envio,tipo FROM argenper_tblSMS where    estado_envio in (6) and cron_intentos < ".NUMERO_CRON_INTENTOS." ";
$rows = DbArgenper::fetchALL($SQL_ESTADO_ENVIO_1); //get all status 1
$datel = date("Y-m-d H:i:s");
foreach($rows as $giro){
        $actual_status = $giro['estado_envio'];    
    try{

        $urlDomain = HTTP_SMS.HTTP_DOMAIN;
        $new_statusOld = generatePostMSG(4, $urlDomain,USER_SMS, PASS_SMS, $giro['id_sms']);
        
        $new_status = trim($new_statusOld);
        
        if($giro['tipo']=='V'){
            if($new_status=='completed'){
                $params = array(
                     'estado_envio' => '4',
                     'fecha_actualizacion_estado' => $datel,
                     'cron_update'=>1
                 );
                 DbArgenper::update('argenper_tblSMS', $params, 'id_ref = ' . $giro['id_ref']); 
                 
                 $paramsInsert = array(
                     'id'=>NULL,
                     'id_ref'=>$giro['id_ref'],
                     'estado_origin'=>'1',
                     'estado_fin'=>'4',
                     'fecha' =>$datel,
                     'response_api'=>''

                 );
                 DbArgenper::insert('argenper_logcron', $paramsInsert);            
            }elseif($new_status=='no-answer' || $new_status=='busy'){
                $msg ="Error Conexion";
                if($new_status=='no-answer'){
                    $msg ="estado llamada sin respuesta";
                }elseif($new_status=='busy'){
                    $msg ="estado llamada ocupado";
                }
                $estado=6;
                if($giro['cron_intentos']==(NUMERO_CRON_INTENTOS-1)){
                    $estado=5;
                }
                $intentos = $giro['cron_intentos'] + 1;
                    $params = array(
                        'estado_envio' => $estado,
                         'respuesta_api'=>$msg,
                         'cron_intentos'=>$intentos
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
            }elseif($new_status=='failed' || $new_status==6){
                $msg ="Failed: Api respuesta";
                $params = array(
                         //'estado_envio'=>5,
                         'estado_envio'=>5,                             
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
            }else{                 
                    $params = array(
                         'estado_envio'=>5,                             
                         'respuesta_api'=>$new_status,
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