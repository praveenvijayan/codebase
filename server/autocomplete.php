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

if(isset($_REQUEST['q'])){
    $result = $_REQUEST['q'];
}

$query = "SELECT id, fld_title FROM tbl_codebase WHERE fld_title LIKE '%$result%' or fld_notes  LIKE '%$result%'";

$tojson = array();
foreach ($db->iterate($query) as $row) {
    array_push($tojson, array('id'=>$row->id, 'title'=>$row->fld_title ));
}
if($tojson){
    echo(json_encode($tojson));
}else{
   echo json_encode(array('result'=>'no result')); 
}
?>
