<?php
include_once('config.php');
if(isset($_REQUEST['id'])){
    $id = $_REQUEST['id'];
    $row = $db->fetchOneRow("SELECT fld_title,fld_html, fld_css, fld_js, fld_options FROM tbl_codebase WHERE id='$id' LIMIT 1");
    $htmldecode = htmlspecialchars_decode($row->fld_html);
    $htmldecode = str_replace("\u000A",'\n',$htmldecode);
    //$css = str_replace("\n",'\u000A',$row->fld_css);
    $css = str_replace("url(",'url(uploads/'.$id.'/',$row->fld_css);
    $js = str_replace("\n",'\u000A',$row->fld_js);
    $options = explode(",", $row->fld_options);
    $jsSrc = array();
    $cssSrc = array();
    foreach ($options as $key => $value){
        $ext =  explode(".", $value);
        
        if(end($ext) == 'js'){
            array_push($jsSrc, '<script type="text/javascript" src="'.$value.'"></script>');
        }else if(end($ext) == 'css'){
            array_push($cssSrc, '<link href="'.$value.'" rel="stylesheet" type="text/css">');
        };
    }
}
?>

<!DOCTYPE HTML>
<html>
<head>
<meta charset="utf-8">
<title><?php echo $row->fld_title; ?></title>
<link href="basestyle.css" rel="stylesheet" type="text/css">
<!--Include option css-->
<?php
foreach ($cssSrc as $key=>$value){
         echo $value;
     }
?>
<style type="text/css">
   <?php echo $css; ?> 
</style>
<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.2/jquery.min.js"></script>
<!--Include option js-->
<?php foreach ($jsSrc as $key=>$value){
         echo $value;
     } ?>
<script>
    $(window).load(function(){
        <?php echo $row->fld_js; ?> 
    })
</script>
</head>

<body>
    <?php echo $htmldecode; ?>
</body>
</html>

