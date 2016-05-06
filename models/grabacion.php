<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
require_once 'view.php';
class GrabacionController extends Controller {
   
    public function getMp3(){
    	$response = array("error"=>0,"message"=>"empty","mp3"=>"");
    	if ($this->getPost('idt') == null) {
			 $response['error'] = 1;
             $response['message'] = "Ingrese Template Valido";
             return json_encode($response);
    	}	
    	$id=$this->getPost('idt');
        $query = "select url,file from argenper_template where id=?";
        $template = DbArgenper::fetchOne($query,$id);
        $html = view::mp3($template);     
        
        return $html;
    }
}

$controller = new GrabacionController();
$controller->run();