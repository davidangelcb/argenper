<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
require_once 'view.php';


class llamadaContactosController extends Controller {
   
    
    public function listar()
    {
         
        $page =  $this->getParam('page'); 
        $limit = $this->getParam('rows'); 
        $sidx =  $this->getParam('sidx'); 
        $sord =  $this->getParam('sord');
        //fechaini='+date1+'&fechafinal
        $idcall =  $this->getParam('idcall');
        
        if(!$sidx) $sidx =1;
        
        $query = "SELECT COUNT(*) AS count "
                . "from llamada_contactos llc 
     inner join llamada ll on ll.id = llc.id_llamada
     inner join argenper_template art on art.id = ll.id_template
     inner join contactos c on c.id = llc.id_contactos
     where ll.id=".$idcall."  and llc.estatus='E'";
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

        $SQL = "SELECT llc.id, c.nombre, c.celular,llc.fecha_proceso, llc.estatus_api,llc.id_sms,
    llc.numero_envios ,llc.response_api
     from llamada_contactos llc 
     inner join llamada ll on ll.id = llc.id_llamada
     inner join argenper_template art on art.id = ll.id_template
     inner join contactos c on c.id = llc.id_contactos
     where 
	  ll.id=".$idcall."
	   and llc.estatus='E' ORDER BY $sidx $sord LIMIT $start , $limit";
        $giros = DbArgenper::fetchAll($SQL);//fetchAll
        
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count; 
        $i=0;
        foreach($giros as $row){
            $row['estatus_api'] = getEstadoDescri($row['estatus_api'], $row['response_api']);
            $response->rows[$i]['id']=$row['id']; 
            $response->rows[$i]['cell']=array($row['id'],$row['nombre'],$row['celular'],$row['fecha_proceso'],$row['id_sms'],$row['numero_envios'],$row['estatus_api']); 
            $i++; 
        }
        return json_encode($response);
    }
}

$controller = new llamadaContactosController();
$controller->run();