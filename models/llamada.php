<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
require_once 'view.php';


class llamadaController extends Controller {
   
    public function add(){
        $response = array("error"=>0, "message"=>"");
        try{
            $ids        = strip_tags($this->getPost('ids'));
            $idTemplate = strip_tags($this->getPost('idtemplate'));
            $datel = date("Y-m-d H:i:s");

            $id = explode(",", $ids);

            $query = "select nombre from directorio  where "  . DbArgenper::sqlIn('id', $id);
            $directorios = DbArgenper::fetchAll($query);
            $nombre = "";
            foreach ($directorios as $directorio) {
                if($nombre==''){
                    $nombre = $directorio['nombre'];
                }else{
                    $nombre.= ",".$directorio['nombre'];
                }
            }

            $params  = array(
                "nombre"=>$nombre,
                "fecha"=>$datel,
                "id_grupos"=>$ids,
                "id_template"=>$idTemplate
            );

            $idllamada = DbArgenper::insert("llamada", $params);
            //select id from contactos  where id_directorio in (1,2) and estatus='E'
            $queryContacts = "select id from contactos  where estatus='E' and "  . DbArgenper::sqlIn('id_directorio', $id);
            $Contacts = DbArgenper::fetchAll($queryContacts);
            $procesarNum = 0;
            foreach ($Contacts as $Contact) {
                $paramsLLamada  = array(
                    "id_llamada"=>$idllamada,
                    "id_contactos"=>$Contact['id'],
                    "fecha_ingreso"=>$datel
                );

                DbArgenper::insert("llamada_contactos", $paramsLLamada);
                $procesarNum++;
            }
            $paramsLLamada  = array(
                    "en_proceso"=>$procesarNum,
            );
            DbArgenper::update('llamada', $paramsLLamada, "id=?",$idllamada);
            
        }  catch (Exception $e){
            $response['error']= 1;
            $response['message']=$e->getMessage();
        }
         return json_encode($response);
    }
}

$controller = new llamadaController();
$controller->run();