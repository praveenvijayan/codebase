<?php

include_once('config.php');

$title = $_POST['title'];

$html = $_POST['html'];

$css = $_POST['css'];

$js = $_POST['js'];

$options = $_POST['options'];

$status = $_POST['status'];


if(isset($_POST['id'])){

   $id= $_POST['id']; 

}



$notes = $_POST['notes'];



//$addSlashes = addslashes($html);

$encode = htmlspecialchars($html);

$js = addslashes($js);





//$json = array('html'=>$encode,'css'=>$css,'js'=>$js,'options'=>$options);

//$jsonencode = json_encode($json);

//$serialize = serialize($jsonencode);



if($status == 'new'){

    $query = "INSERT INTO tbl_codebase(fld_title,fld_html, fld_css, fld_js, fld_notes, fld_options) VALUES ('$title','$encode','$css','$js','$notes','$options')";

    //$query = "INSERT INTO tbl_codebase VALUES (NULL, 'test', NULL)";

    $last_id = $db->insert($query);

    echo json_encode(array('id'=>$last_id));  

}



if($status == 'update'){

     $query = "UPDATE tbl_codebase SET fld_title='$title',fld_html='$encode', fld_css='$css', fld_js='$js',fld_notes='$notes', fld_options='$options' WHERE id='$id'";

     $last_id = $db->insert($query);

     echo json_encode(array('id'=>$id));   

}



if($status == 'delete'){

    $query = "DELETE FROM tbl_codebase WHERE id='$id'";

    $last_id = $db->insert($query);

}



?>

