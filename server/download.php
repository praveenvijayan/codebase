<?php
include_once('config.php');
if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $row = $db->fetchOneRow("SELECT fld_title,fld_html, fld_css, fld_js, fld_options FROM tbl_codebase WHERE id='$id' LIMIT 1");
    $htmldecode = htmlspecialchars_decode($row->fld_html);
    $htmldecode = str_replace("\u000A",'\n',$htmldecode);
    $css = str_replace("\n",'\u000A',$row->fld_css);
    $js = str_replace("\n",'\u000A',$row->fld_js);
    $options = explode(",", $row->fld_options);
    $jsSrc = array();
    $cssSrc = array();
    foreach ($options as $key => $value){
        $ext =  explode(".", $value);
        $localSrc = str_replace("uploads/".$id.'/','',$value);
        if(end($ext) == 'js'){
            array_push($jsSrc, '<script type="text/javascript" src="'.$localSrc.'"></script>');
        }else if(end($ext) == 'css'){
            array_push($cssSrc, '<link href="'.$localSrc.'" rel="stylesheet" type="text/css">');
        };
    }	
}
?>
<?php 
$pageTitle = $row->fld_title;

$cssVal = "";
foreach ($cssSrc as $key=>$value){
    $cssVal .= $value;
}

$jsVal ="";
foreach ($jsSrc as $key=>$value){
    $jsVal .= $value;
}
	
$inlineCSS = $row->fld_css;
$inlinejs = $row->fld_js;



$string = <<<EOT
<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title>$pageTitle</title>
<link href="basestyle.css" rel="stylesheet" type="text/css">
<!--Include option css-->
$cssVal
<style type="text/css">
   $inlineCSS
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<!--Include option js-->
$jsVal
<script>
    $(window).load(function(){
        $inlinejs
    })
</script>
</head>

<body>
   $htmldecode
</body>
</html>
EOT;
?>
<?php
if(isset($_REQUEST['id'])){
    
        if (!file_exists('uploads/'.$id)) { 
            mkdir('uploads/'.$id, 0777);
        }
        
	$ourFileName = "index-".$id.".html";
	$ourFileHandle = fopen('uploads/'.$id.'/'.$ourFileName, 'w') or die("can't open file");
	fwrite($ourFileHandle, $string);
	fclose($ourFileHandle);
        
        function Zip($source, $destination){
        if (!extension_loaded('zip') || !file_exists($source)) {
            return false;
        }

        $zip = new ZipArchive();
        if (!$zip->open($destination, ZIPARCHIVE::CREATE)) {
            return false;
        }

        $source = str_replace('\\', '/', realpath($source));

        if (is_dir($source) === true)
        {
            $files = new RecursiveIteratorIterator(new RecursiveDirectoryIterator($source), RecursiveIteratorIterator::SELF_FIRST);

            foreach ($files as $file)
            {
                $file = str_replace('\\', '/', realpath($file));

                if (is_dir($file) === true)
                {
                    $zip->addEmptyDir(str_replace($source . '/', '', $file . '/'));
                }
                else if (is_file($file) === true)
                {
                    $zip->addFromString(str_replace($source . '/', '', $file), file_get_contents($file));
                }
            }
        }
        else if (is_file($source) === true)
        {
            $zip->addFromString(basename($source), file_get_contents($source));
        }

    return $zip->close();
    }
    
    $source = 'uploads/'.$id;
    $destination = 'uploads/'.$id.'/'.$id.'.zip';
    Zip($source,$destination);
    
    header('Content-Type: application/zip');
    header('Content-Length: ' . filesize($source."/".$id.'.zip'));
    header('Content-Disposition: attachment; filename="'.$id.'.zip"');
    readfile($source."/".$id.'.zip');
    unlink($source."/".$id.'.zip'); 
    unlink($source."/".$ourFileName);
    
}
?>