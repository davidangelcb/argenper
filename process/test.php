<?php
require_once("../lib/config.php");
require_once("../lib/funcs.php");
require_once("../lib/load.php"); 
//function generateGetMSG($url,$user, $pass, $para, $msg=''){

$urlDomain = HTTP_SMS.HTTP_DOMAIN;
$url  = generateGetMSG( $urlDomain,USER_SMS, PASS_SMS, '51944540978', 'https://mensajesonline.s3.amazonaws.com/sounds/ca2e92074f808c9302bb0fe8607a3189.mp3');

echo $url;