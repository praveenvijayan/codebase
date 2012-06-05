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

if(isset($_REQUEST['range'])){
    $recLimit = $_REQUEST['range'];
}else{
    $recLimit = 10;
}

if(isset($_REQUEST['page'])){
    $page = $_REQUEST['page'];
    $offset = $recLimit * $page;
}  else {
     $page = 0;
     $offset = 0;
} 

//Paging
$count = $db->fetchOne("SELECT COUNT(*) FROM tbl_codebase");

if(isset($_REQUEST['q']) && $_REQUEST['q'] != "" ){
    $result = $_REQUEST['q'];
    $query = "SELECT id, fld_title,fld_html,fld_css,fld_js,fld_notes,fld_options FROM tbl_codebase WHERE fld_title LIKE '%$result%' or fld_notes  LIKE '%$result%'";
}else{
    $query = "SELECT id, fld_title,fld_html,fld_css,fld_js,fld_notes,fld_options FROM tbl_codebase ORDER BY fld_modified DESC LIMIT $offset, $recLimit ";
}


$tojson = array();
foreach ($db->iterate($query) as $row) {
    $html = htmlspecialchars_decode($row->fld_html);
    //$jsonDecode = json_decode($html);    
    array_push($tojson, array('id'=>$row->id, 'title'=>$row->fld_title, 'html' =>$html, 'css'=>$row->fld_css, 'js'=>$row->fld_js,'notes'=>$row->fld_notes, 'options'=>$row->fld_options, 'pageCount'=>$count));
}
echo(json_encode($tojson));
 
?>
