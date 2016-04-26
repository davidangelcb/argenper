<?php
require_once("../lib/config.php");
require_once("../lib/funcs.php");
require_once("../lib/load.php"); 

//captura los ID que necesitan ser actualizado

$SQL_ESTADO_ENVIO_1 = "SELECT   fecha_proceso,id_sms, id,estatus_api FROM 
llamada_contactos where  cron_update=0 and estatus_api in (1) 
and fecha_proceso is not null and now()<DATE_ADD(fecha_proceso, INTERVAL 8 DAY)";
$rows = DbArgenper::fetchALL($SQL_ESTADO_ENVIO_1); //get all status 1
$datel = date("Y-m-d H:i:s");
foreach($rows as $giro){
        $actual_status = $giro['estatus_api'];    
    try{

        $urlDomain = HTTP_SMS.HTTP_DOMAIN;
 
        $new_statusOld = generateGetMSG(2, $urlDomain,USER_SMS, PASS_SMS, $giro['id_sms']);
        echo $new_statusOld."<br>";
        $new_status = trim($new_statusOld);
        
           
        if($new_status=='completed'){
            $params = array(
                 'estatus_api' => '4',
                 'fecha_actualizacion' => $datel,
                 'cron_update'=>1
             );
             DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']); 
             //argenper_logcron    id   id_ref   estado_origin  estado_fin   fecha  response_api
             $paramsInsert = array(
                 'id'=>NULL,
                 'id_ref'=>$giro['id'],
                 'estado_origin'=>$actual_status,
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
            $params = array(
                     'estatus_api'=>6,                             
                     'response_api'=>$msg
                 );
                 DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']);   

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
                     'estatus_api'=>5,                             
                     'response_api'=>$msg
                 );
                 DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']);   

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
                     //'estado_envio'=>5,
                     'estatus_api'=>5,                             
                     'response_api'=>$new_status
                 );
                 DbArgenper::update('llamada_contactos', $params, 'id_ref = ' . $giro['id_ref']);   

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