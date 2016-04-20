<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");

class GrabacionController extends Controller {
   
    public function lista(){
        
    }
}

$controller = new GrabacionController();
$controller->run();