<?php

require_once("../lib/config.php");
require_once("../lib/funcs.php");
require_once("../lib/load.php");

try {
    $llamada = array();
    $procesa = true;
    $SQL_ESTADO_ENVIO_1 = "select llc.id, llc.id_llamada, llc.id_contactos, art.url, c.celular, llc.numero_envios 
     from llamada_contactos llc 
     inner join llamada ll on ll.id = llc.id_llamada
     inner join argenper_template art on art.id = ll.id_template
     inner join contactos c on c.id = llc.id_contactos
     where llc.estatus_api=1 and llc.estatus='E' limit 50";
    $giros = DbArgenper::fetchALL($SQL_ESTADO_ENVIO_1); //get all status 1
    $ids = "";
    $callsVoices = $giros;
    foreach ($giros as $girito) {
        if ($ids == "") {
            $ids = $girito['id'];
        } else {
            $ids.="," . $girito['id'];
        }
    }
    print_r($callsVoices);
    $datel = date("Y-m-d H:i:s");
    if ($ids != "") {
        $arrayIds = explode(",", $ids);
        $query = "update llamada_contactos set fecha_proceso='" . $datel . "' ,estatus_api=101 where " . DbArgenper::sqlIn('id', $arrayIds);
        //DbArgenper::exec($query);    
    } else {
        $procesa = false;
    }

    $iduser = 0;
    if ($procesa) {
        $cantGiros = 0;
        $idsSend = "";
        $idsFail = "";
        foreach ($callsVoices as $giro) {


            $datel = date("Y-m-d H:i:s");

            $urlDomain = HTTP_SMS . HTTP_DOMAIN;
            $tipoMensaje = valNumeroTelefonico($giro['celular']);
            if ($tipoMensaje['value'] == 'C') {
                $nx = 0;
                $url = generateGetMSG($urlDomain, USER_SMS, PASS_SMS, $tipoMensaje['numero'], $giro['url']);
                echo $url . "<br>";
                $dat = trim($url);
                $dataApi = explode("-|-", $dat);
                if (count($dataApi) > 1) {
                    $params = array(
                        'estatus_api' => 0,
                        'fecha_proceso' => $datel,
                        'response_api' => $dataApi[1],
                    );
                    DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']);

                    $llamada = "update llamada set errores=errores+1 where id = ?";
                    DbArgenper::exec($llamada, $giro['id_llamada']);

                    $idsFail .= ($idsFail == "") ? $giro['id'] : "," . $giro['id'];
                } else {
                    $res = explode(" ", $dataApi[0]);
                    if (count($res) == 2) {
                        if ($res[0] == 'OK') {
                            $giroNUM = $giro['numero_envios'] + 1;
                            $params = array(
                                'estatus_api' => 1,
                                'fecha_proceso' => $datel,
                                'id_sms' => $res[1],
                                'response_api' => 'Done',
                                'numero_envios' => $giroNUM
                            );
                            DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']);

                            $llamada = "update llamada set correctos=correctos+1 where id = ?";
                            DbArgenper::exec($llamada, $giro['id_llamada']);

                            $idsSend .= ($idsSend == "") ? $giro['id'] : "," . $giro['id'];
                        } else {
                            $params = array(
                                'estatus_api' => 0,
                                'fecha_proceso' => $datel,
                                'id_sms' => $res[1],
                                'respuesta_api' => "Error, Contacte con en Area Soporte"
                            );
                            DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']);

                            $llamada = "update llamada set errores=errores+1 where id = ?";
                            DbArgenper::exec($llamada, $giro['id_llamada']);

                            $idsFail .= ($idsFail == "") ? $giro['id'] : "," . $giro['id'];
                        }
                    } else {
                        $params = array(
                            'estatus_api' => 0,
                            'fecha_proceso' => $datel,
                            'respuesta_api' => "Error, Contacte con en Area Soporte",
                        );
                        DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']);

                        $llamada = "update llamada set errores=errores+1 where id = ?";
                        DbArgenper::exec($llamada, $giro['id_llamada']);

                        $idsFail .= ($idsFail == "") ? $giro['id'] : "," . $giro['id'];
                    }
                }
            } else {
                $params = array(
                    'estatus_api' => 0,
                    'fecha_proceso' => $datel,
                    'respuesta_api' => "Error Numero Celular",
                );
                DbArgenper::update('llamada_contactos', $params, 'id = ' . $giro['id']);

                $llamada = "update llamada set errores=errores+1 where id = ?";
                DbArgenper::exec($llamada, $giro['id_llamada']);
            }
        }
    }
} catch (ErrorException $E) {
    echo $E->getMessage();
    echo "<br>";
    echo $E->getTraceAsString();
}