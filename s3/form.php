<?php
/**
* $Id$
*
* S3 form upload example
*/
if (!class_exists('S3')) require_once 'S3.php';
// AWS access info
if (!defined('awsAccessKey')) define('awsAccessKey', 'AKIAJB5UGPX76AMUSEMQ');
if (!defined('awsSecretKey')) define('awsSecretKey', '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');
// Check for CURL
if (!extension_loaded('curl') && !@dl(PHP_SHLIB_SUFFIX == 'so' ? 'curl.so' : 'php_curl.dll'))
	exit("\nERROR: CURL extension not loaded\n\n");
// Pointless without your keys!
if (awsAccessKey == 'change-this' || awsSecretKey == 'change-this')
	exit("\nERROR: AWS access information required\n\nPlease edit the following lines in this file:\n\n".
	"define('awsAccessKey', 'change-me');\ndefine('awsSecretKey', 'change-me');\n\n");
S3::setAuth(awsAccessKey, awsSecretKey);
$bucket = 'mensajesonline';
$path = 'sounds/'; // Can be empty ''
$lifetime = 3600; // Period for which the parameters are valid https://s3-sa-east-1.amazonaws.com/mensajesonline/
$maxFileSize = (1024 * 1024 * 50); // 50 MB
$metaHeaders = array('uid' => 123);
$requestHeaders = array(
    'Content-Type' => 'application/octet-stream',
    'Content-Disposition' => 'attachment; filename=${filename}'
);
$params = S3::getHttpUploadPostParams(
    $bucket,
    $path,
    S3::ACL_PUBLIC_READ,
    $lifetime,
    $maxFileSize,
    201, // Or a URL to redirect to on success
    $metaHeaders,
    $requestHeaders,
    false // False since we're not using flash
);
//https://s3-sa-east-1.amazonaws.com/mensajesonline/
//https://s3-sa-east-1.amazonaws.com/mensajesonline/
$uploadURL = 'https://' . $bucket . '.s3.amazonaws.com/';
?><!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <title>S3 Form Upload</title>
</head>
<body>
    <form method="post" action="<?php echo $uploadURL; ?>" enctype="multipart/form-data">
<?php
    foreach ($params as $p => $v)
        echo "        <input type=\"hidden\" name=\"{$p}\" value=\"{$v}\" />\n";
?>
        <input type="file" name="file" />&#160;<input type="submit" value="Upload" />
    </form>
</body>
</html>