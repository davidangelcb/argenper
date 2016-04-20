<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
require_once 'view.php';

class DirectorioController extends Controller {
   
    public function listar(){
        $query = "select id,nombre, codigo, miembros from directorio where estatus=?";
        $directorio = DbArgenper::fetchAll($query,'E');
        $html = view::grupos($directorio);     
        
        $response =  array( "html"=>$html);
        return json_encode($response);
    }
    
    public function addGrupo(){
        $response = array("error"=>0,"message"=>"empty", "id"=>0);
        
         if ($this->getPost('name') == null) {
             $response['error'] = 1;
             $response['message'] = "Ingrese Nombre del Grupo";
             return json_encode($response);
         }
         $name = strip_tags($this->getPost('name'));
         $datel = date("Y-m-d H:i:s");
         $params = array(
             "fecha_creacion" =>  $datel,
             "nombre"=>$name,
             "codigo"=> $this->getCode(),
             "miembros"=>0
         );
         $id = DbArgenper::insert("directorio", $params);
         $response['id'] = $id;
         
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
    
    public function deleteGrupo(){
       $response = array("error"=>0,"message"=>"empty", "id"=>0);
        try{        
        
         if ($this->getPost('ids') == null) {
             $response['error'] = 1;
             $response['message'] = "Seleccione Grupo(s)";
             return json_encode($response);
         }
         $ids = strip_tags($this->getPost('ids'));
          
         $id = explode(",", $ids);
        
         $query = "update  directorio set estatus='D'  where "  . DbArgenper::sqlIn('id', $id);
         DbArgenper::exec($query);    
                  
        }  catch (Exception $e){
            $response['error']= 1;
            $response['message']=$e->getMessage();
        }
         return json_encode($response);
    }
     public function listarSelect(){
        $query = "select id,nombre  from directorio where estatus=?";
        $directorio = DbArgenper::fetchAll($query,'E');
        $html = view::selectGrupos($directorio);     
         
        return $html;
    }
    public function getCode(){
        $data = md5unico();
        $rest = substr($data, 0, 6); 
        return strtoupper($rest);
    }
}

$controller = new DirectorioController();
$controller->run();
 