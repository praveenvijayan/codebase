<?php
include_once('mysqldatabase.php');
include_once('mysqlresultset.php');
// get the MySqlDatabase instance
$db = MySqlDatabase::getInstance();
try {
    //$conn = $db->connect('localhost', 'root', '', 'db_codebase');
    $conn = $db->connect('localhost', 'codebase_root', 'password', 'codebase_db');
} 
catch (Exception $e) {
    die($e->getMessage());
}
?>
