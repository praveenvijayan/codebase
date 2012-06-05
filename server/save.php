<?php
include_once('mysqldatabase.php');
include_once('mysqlresultset.php');
// get the MySqlDatabase instance
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect('localhost', 'root', '', 'db_codebase');
} 
catch (Exception $e) {
    die($e->getMessage());
}

$title = $_REQUEST['title'];
$html = $_REQUEST['html'];
$css = $_REQUEST['css'];
$js = $_REQUEST['js'];
$options = $_REQUEST['options'];
$flag = $_REQUEST['flag'];

if(isset($_REQUEST['id'])){
   $id= $_REQUEST['id']; 
}

$notes = $_REQUEST['notes'];

$addSlashes = addslashes($html);
$encode = htmlspecialchars($addSlashes);

$js = addslashes($js);


//$json = array('html'=>$encode,'css'=>$css,'js'=>$js,'options'=>$options);
//$jsonencode = json_encode($json);
//$serialize = serialize($jsonencode);

if($flag == 'new'){
    $query = "INSERT INTO tbl_codebase(fld_title,fld_html, fld_css, fld_js, fld_notes, fld_options) VALUES ('$title','$encode','$css','$js','$notes','$options')";
    //$query = "INSERT INTO tbl_codebase VALUES (NULL, 'test', NULL)";
    $last_id = $db->insert($query);
    echo json_encode(array('id'=>$last_id));  
}

if($flag == 'update'){
     $query = "UPDATE tbl_codebase SET fld_title='$title',fld_html='$encode', fld_css='$css', fld_js='$js',fld_notes='$notes', fld_options='$options' WHERE id='$id'";
     $last_id = $db->insert($query);
     echo json_encode(array('id'=>$id));   
}

if($flag == 'delete'){
    $query = "DELETE FROM tbl_codebase WHERE id='$id'";
    $last_id = $db->insert($query);
}

?>
