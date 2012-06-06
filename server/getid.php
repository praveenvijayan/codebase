<?php
include_once('config.php');

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
