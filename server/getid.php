<?php
include_once('mysqldatabase.php');
include_once('mysqlresultset.php');
$db = MySqlDatabase::getInstance();
try {
    $conn = $db->connect('localhost', 'root', '', 'db_codebase');
} 
catch (Exception $e) {
    die($e->getMessage());
}

$row = $db->fetchOneRow("SELECT id FROM tbl_codebase ORDER BY id DESC LIMIT 1");


if($row){
    echo $row->id;
}else{
   echo 0; 
};

//echo $row->id;

//return $id;

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
