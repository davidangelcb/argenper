<?php
// genera un md5 unico
function md5unico()
{
	mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	$charid = md5(uniqid(rand(), true));
	return $charid;
}
//  Generates a Globally Unique Identifier (GUID) and returns it as a string
function guid($k=1)
{
	mt_srand((double)microtime()*10000);//optional for php 4.2.0 and up.
	$charid = strtoupper(md5(uniqid(rand(), true)));
	$hyphen = chr(45); // "-"
	$uuid = substr($charid, 0, 8).$hyphen
			.substr($charid, 8, 4).$hyphen
			.substr($charid,12, 4).$hyphen
			.substr($charid,16, 4).$hyphen
			.substr($charid,20,12);
	if ($k==1)
		$uuid = chr(123).$uuid.chr(125);
	return $uuid;
}
function sessionValid()
{
    ini_set("session.name",SESS_COOKIE_NAME);
	if(isset($_COOKIE[SESS_COOKIE_NAME]))
	{
            if (!defined('SESSION_INIT')) {

                session_start();
                define('SESSION_INIT', true);
            }
		// validacion de sesion
		if( isset($_SESSION['bsid']) and ($_SESSION['bsid'] === session_id()) )
		{
			if( isset($_SESSION['guid']) and !empty($_SESSION['guid']) )
			{
			    $timeLimit = SESS_TIME_EXPIRE;
				if( !empty($timeLimit) )
				{
					// validacion de tiempo de expiracion de session
					$delay = time() - $_SESSION['lastuse'];
					if( $delay > (60*$timeLimit) )
					{
						session_unset();
						session_destroy();
						unset($_COOKIE[SESS_COOKIE_NAME]);
						return false;
					}
				}
				// todo ok
				$_SESSION['lastuse'] = time();
				return true;
			} else {
				session_unset();
				session_destroy();
				unset($_COOKIE[SESS_COOKIE_NAME]);
				return false;
			}
		} else {
			session_unset();
			session_destroy();
			unset($_COOKIE[SESS_COOKIE_NAME]);
			return false;
		}
	// hacer esto si no tiene acceso a la herramienta
	} else {
		return false;
	}
}
function generatePostVoice($url,$user, $pass, $para){

    try{
        $a = "ta=pc&u=".$user."&p=".$pass."&to=".$para;
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $a
            )
        );

        $context  = stream_context_create($opts);

        $response = file_get_contents($url, false, $context);
         
    }  catch (Exception $e){
        $response = 'Error-|-'.  $e->getMessage();
    }    
    return $response;
}
function generatePostMSG($tipo, $url,$user, $pass, $para, $msg=''){

    try{
        switch($tipo){
            case 1:$a  = "u=".$user."&p=".$pass."&to=".$para."&msg=".$msg; break;
            case 2:$a  = "ta=pc&u=".$user."&p=".$pass."&to=".$para; break;
            case 3:$a  = "ta=ds&u=".$user."&p=".$pass."&slid=".$para; break;
            case 4:$a  = "ta=cs&u=".$user."&p=".$pass."&clid=".$para; break;
        }

        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $a
            )
        );

        $context  = stream_context_create($opts);

        $response = file_get_contents($url, false, $context);
         
    }  catch (Exception $e){
        $response = 'Error-|-'.  $e->getMessage();
    }    
    return $response;
}
function generateGetMSG($url,$user, $pass, $para, $msg=''){

    try{//https://www.mensajesonline.pe/sendsms?app=webservices&ta=pc&u=sms_online&p=P@ssw0rd!2014&to=997720815&grabacion=http://amazon.com/play1

        $a  = "ta=pc&u=".$user."&p=".$pass."&to=".$para."&grab=".$msg;
 
        $opts = array('http' =>
            array(
                'method'  => 'POST',
                'header'  => 'Content-type: application/x-www-form-urlencoded',
                'content' => $a
            )
        );

        $context  = stream_context_create($opts);

        $response = file_get_contents($url, false, $context);
         
    }  catch (Exception $e){
        $response = 'Error-|-'.  $e->getMessage();
    }    
    return $response;
}
function valTipoPhone($cel){
    $response = array( "value"=> 'T',"numero"=>$cel);
    $cel = str_replace("+", '', $cel);
    $c = strlen($cel);
    
    if($c==8){
        $response = array( "value"=> 'V',"numero"=>'51'.$cel);
    }elseif($c==10){
        $tmp = substr($cel,0,2);
        if($tmp == 51 || $tmp =='51'){
            $response = array( "value"=> 'V',"numero"=>$cel);
        }
    }
    return $response;
}
function valNumeroTelefonico($cel){
    $response = array( "value"=> 'F',"numero"=>$cel);
    $celular = str_replace("+", '', $cel);
    $c = strlen($celular);
    
    if($c==9){
        $response = array( "value"=> 'C',"numero"=>'51'.$celular);
    }elseif($c==11){
        $tmp = substr($cel,0,2);
        if($tmp == 51 || $tmp =='51'){
            $response = array( "value"=> 'C',"numero"=>$celular);
        }
    }
    return $response;
}
function validacionVoice($cel, $arg){
    $response='';
    if($arg=='XX'){
        $response = 'Argenper Validacion';
    }
    /*if($arg=='EE'){
        return 'P';
    }*/
    if($cel==''){
        $response = 'Campo requerido';
    }    
    return $response;
}
function  validacion($cel,$msg,$arg){
    if($arg=='XX'){
        return 'Argenper Validacion';
    }
    if($arg=='EE'){
        return 'P';
    }
    if($cel==''){
        return 'Campo requerido';
    }
    if($msg==''){
        return 'Campo requerido';
    }
    if(strlen($msg)>160){
        return 'Longitud excedida('.strlen($msg).')';
    }
    $c = strlen($cel);
    if($c==9){
        $tmp = substr($cel,0,1);
        if($tmp == 9 || $tmp =='9'){
            if(preg_match('/^[0-9]{9}$/',$cel)){
                return '';
            }else{
                return 'Validacion Celular(1)';
            }
         }else{
             return 'Validacion Celular(2)';
         }
    }elseif($c==11){
        $tmp = substr($cel,0,3);
        if($tmp == 519 || $tmp =='519'){
            if(preg_match('/^[0-9]{9}$/',$cel)){
                return '';
            }else{
                return 'Validacion Celular(3)';
            }
         }else{
             return 'Validacion Celular(4)';
         }        
    }elseif($c==12){
        $tmp = substr($cel,0,4);
        if($tmp =='+519'){
            $xcel = str_replace("+", '', $cel);
            if(preg_match('/^[0-9]{9}$/',$xcel)){
                return '';
            }else{
                return 'Validacion Celular(5)';
            }
         }else{
             return 'Validacion Celular(6)';
         }
    }else{
        return 'Validacion Celular(7)';
    }
}
function  validacionTwo($cel,$msg,$arg){
    if($arg=='XX'){
        return 'Argenper Validacion';
    }
    if($cel==''){
        return 'Campo requerido';
    }
    if($msg==''){
        return 'Campo requerido';
    }
    if(strlen($msg)>160){
        return 'Longitud excedida('.strlen($msg).')';
    }
    $c = strlen($cel);
    if($c==9){
        $tmp = substr($cel,0,1);
        if($tmp == 9 || $tmp =='9'){
            if(preg_match('/^[0-9]{9}$/',$cel)){
                return '';
            }else{
                return 'Validacion Celular(1)';
            }
         }else{
             return 'Validacion Celular(2)';
         }
    }elseif($c==11){
        $tmp = substr($cel,0,3);
        if($tmp == 519 || $tmp =='519'){
            if(preg_match('/^[0-9]{9}$/',$cel)){
                return '';
            }else{
                return 'Validacion Celular(3)';
            }
         }else{
             return 'Validacion Celular(4)';
         }        
    }elseif($c==12){
        $tmp = substr($cel,0,4);
        if($tmp =='+519'){
            $xcel = str_replace("+", '', $cel);
            if(preg_match('/^[0-9]{9}$/',$xcel)){
                return '';
            }else{
                return 'Validacion Celular(5)';
            }
         }else{
             return 'Validacion Celular(6)';
         }
    }else{
        return 'Validacion Celular(7)';
    }
}
?>
