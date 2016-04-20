<?php
require_once '../lib/config.php';
require_once("../lib/controller.php");
require_once("../lib/load.php");
require_once("../lib/funcs.php");
require_once('../lib/upload_class.php');
require_once('../lib/S3.php');
$msg= '';
$muestra = false;
$my_upload = new file_upload;
$my_upload->upload_dir = "../temp/"; // the (absolute) folder for the uploaded files 
$my_upload->extensions = array(".mp3", ".wav"); // specify the allowed extensions here
//  if (is_uploaded_file($_FILES['filename']['tmp_name'])) {
if(isset($_FILES['upload']['name'])) {
	$my_upload->the_temp_file = $_FILES['upload']['tmp_name'];
	$my_upload->the_file = $_FILES['upload']['name'];
	$my_upload->http_error = $_FILES['upload']['error'];
	if($my_upload->upload()) {
		$size=GetImageSize('../temp/'.$my_upload->pathFile);
		$xy=$size[0]."x".$size[1];
 
			if(file_exists("../temp/".trim($my_upload->pathFile))){
                            try{
				$newname = awsToolsPrefix.md5unico(trim($my_upload->pathFile)).strtolower(strrchr(trim($my_upload->pathFile),"."));
				$s3 = new S3(awsAccessKey, awsSecretKey,false);
                                $s3->putBucket(awsBucket, S3::ACL_PUBLIC_READ);
                              //if(          $s3->putObjectFile($tmp, $bucket , $actual_image_name, S3::ACL_PUBLIC_READ) )
				$uploaded1 = $s3->putObjectFile("../temp/".trim($my_upload->pathFile), awsBucket, $newname, S3::ACL_PUBLIC_READ);
				if($uploaded1){
					unlink("../temp/".trim($my_upload->pathFile));
                                        $name = $_POST['nombregra'];
                                        $s3link="https://".awsBucket.".s3.amazonaws.com/".$newname;
                                        $params = array(
                                            "fecha" => date("Y-m-d H:i:s"),
                                            "titulo"=>  strip_tags($name),
                                            "url" => $s3link, 
                                            "file"=> $_FILES['upload']['name']
                                        );
                                        DbArgenper::insert('argenper_template', $params);
					$muestra=true;
				}else{
                                    $msg= "error upload:".print_r($uploaded1);
				}
                              }  catch (Exception $Ex){
                                  $msg = $Ex->getMessage();
                              }
			}else{
                            $msg="Error al Subir el Archivo";
			}
		 
	}
	if($my_upload->codeError){
		$muestra = false;
		$msg = strip_tags($my_upload->message);	
	}
}
?>
<script>
<?php if($muestra){
?>
        alert("Se Grabo de manera exitosa!");
	parent.closeGrabacion();
<?php } else {
         if($msg!=''){
        ?> 
            parent.bloqueUpload=true;
            alert("<?php echo $msg;?>");	
        <?php 
         }
}
?>
</script>