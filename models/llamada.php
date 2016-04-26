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
                    "fecha_ingreso"=>$datel,
                    "estatus_api"=>100
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
    public function listar()
    {
         
        $page =  $this->getParam('page'); 
        $limit = $this->getParam('rows'); 
        $sidx =  $this->getParam('sidx'); 
        $sord =  $this->getParam('sord');
        //fechaini='+date1+'&fechafinal
        $fechaini =  $this->getParam('fechaini');
        $fechafinal =  $this->getParam('fechafinal');
        
        if(!$sidx) $sidx =1;
        
        $query = "SELECT COUNT(*) AS count from  llamada  where fecha > '".$fechaini." 00:00:00' and fecha < '".$fechafinal." 23:59:50' and estatus='E'";
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

        $SQL = "SELECT   id,   fecha, nombre, en_proceso, correctos , errores FROM  llamada where fecha > '".$fechaini." 00:00:00' and fecha < '".$fechafinal." 23:59:50' and estatus='E'  ORDER BY id desc LIMIT $start , $limit"; 
        $giros = DbArgenper::fetchAll($SQL);//fetchAll
        
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count; 
        $i=0;
        foreach($giros as $row){
            $response->rows[$i]['id']=$row['id']; 
            $response->rows[$i]['cell']=array($row['id'],$row['fecha'],$row['nombre'],$row['en_proceso'],$row['correctos'],$row['errores'] ); 
            $i++; 
        }
        return json_encode($response);
    }
}

$controller = new llamadaController();
$controller->run();