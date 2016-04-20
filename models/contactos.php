<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
require_once 'view.php';

class contactosController extends Controller {
   
    public function listar(){
         
        $page =  $this->getParam('page'); 
        $limit = $this->getParam('rows'); 
        $sidx =  $this->getParam('sidx'); 
        $sord =  $this->getParam('sord');
        $id = strip_tags($this->getParam('directorio'));
        
        if(!$sidx) $sidx =1;
        
        $query = "SELECT COUNT(*) AS count from contactos where id_directorio=? and estatus='E'";
	$dataTotal = DbArgenper::fetchOne($query, $id);//

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

        $SQL = "select id, nombre, celular, valor1,valor2,email from contactos where id_directorio=? and estatus='E'  ORDER BY $sidx $sord LIMIT $start , $limit"; 
        $giros = DbArgenper::fetchAll($SQL,$id);//fetchAll
        
        $response->page = $page; 
        $response->total = $total_pages; 
        $response->records = $count; 
        $i=0;
        foreach($giros as $row){
            $response->rows[$i]['id']=$row['id']; 
            $response->rows[$i]['cell']=array($row['id'],$row['nombre'],$row['celular'],$row['valor1'],$row['valor2'],$row['email']); 
            $i++; 
        }
        return json_encode($response);
        
        
        /*
        $id = strip_tags($this->getPost('directorio'));
        
        $query = "select id, nombre, celular, valor1,valor2,email from contactos where id_directorio=? and estatus='E'";
        $directorio = DbArgenper::fetchAll($query,$id);
        $html = view::contactos($directorio);     
        
        $response =  array( "html"=>$html);
        return json_encode($response);*/
    }
    public function add(){
        $response = array("error"=>0,"message"=>"empty", "id"=>0);
        
         if ($this->getPost('nombre') == null) {
             $response['error'] = 1;
             $response['message'] = "Ingrese Nombre";
             return json_encode($response);
         } 
         $celular = strip_tags($this->getPost('celular'));
         $email = strip_tags($this->getPost('email'));
         $nombre = strip_tags($this->getPost('nombre'));
         $valor1 = strip_tags($this->getPost('valor1'));
         $valor2 = strip_tags($this->getPost('valor2'));
         $directorio =  $this->getParam('directorio');
         $datel = date("Y-m-d H:i:s");
         $text = validacion($celular,'Demo','AS');
         if($text!=''){
             $response['error'] = 1;
             $response['message'] = "Ingrese Numero de Celular Correcto";
             return json_encode($response);
         }
         $params = array(
             "nombre"=> $nombre,
             "celular"=> $celular,
             "valor1"=> $valor1,
             "valor2"=> $valor2,
             "email"=>$email,
             "fecha_actualizacion"=>$datel,
             "id_directorio"=>$directorio
         );//nombre, celular, valor1,valor2,email
         $id = DbArgenper::insert("contactos", $params);
         $queryUpdate= "update directorio set miembros=miembros+1 where id=".$directorio;
         DbArgenper::exec($queryUpdate);
         $response['id'] = $id;
         $response['message'] = 'Se Agrego el Registro';
         return json_encode($response);
    }
    public function delete(){
        $response = array("error"=>0,"message"=>"empty", "id"=>0);
        try{ 
            $ids = strip_tags($this->getPost('ids'));
            $id = explode(",", $ids);
            $num = 0;
            for($i=0; $i<count($id); $i++){
               if((int)$id[$i] >0){
                 $num++;  
               }  
            }
            $query = "update  contactos set estatus='D'  where "  . DbArgenper::sqlIn('id', $id);
            DbArgenper::exec($query);
            
            $contact = DbArgenper::fetchOneBy("id=".$id[1], "contactos");
            
            $queryUpdate= "update directorio set miembros=miembros-".$num." where id=".$contact['id_directorio'];
            DbArgenper::exec($queryUpdate);
            $response['message'] = $queryUpdate;
         }  catch (Exception $e){
            $response['error']= 1;
            $response['message']=$e->getMessage();
        }
         return json_encode($response);
    }
    public function updateGrupo(){
       $response = array("error"=>0,"message"=>"empty", "id"=>0);
        try{        
        
         if ($this->getPost('name') == null) {
             $response['error'] = 1;
             $response['message'] = "Ingrese Nombre del Grupo";
             return json_encode($response);
         }
         $name = strip_tags($this->getPost('name'));
         $id = strip_tags($this->getPost('directorio'));
         $datel = date("Y-m-d H:i:s");
         $params = array(
             "fecha_actualizacion" =>  $datel,
             "nombre"=>$name
         );
         DbArgenper::update("directorio", $params, "id=?", $id);         
         
        }  catch (Exception $e){
            $response['error']= 1;
            $response['message']=$e->getMessage();
        }
         return json_encode($response);
    }
    public function getCode(){
        $data = md5unico();
        $rest = substr($data, 0, 6); 
        return strtoupper($rest);
    }
}

$controller = new contactosController();
$controller->run();