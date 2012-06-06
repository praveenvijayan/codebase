<?php

include_once('config.php');

?>

<!DOCTYPE HTML>

<html>

<head>

<meta charset="utf-8">

<title>Codebase</title>

<link href="style.css" rel="stylesheet" type="text/css">

<link href="uploadify/uploadify.css" rel="stylesheet" type="text/css">

<link href="jqueryFileTree/jqueryFileTree.css" rel="stylesheet" type="text/css">

<script type="text/javascript" src="https://ajax.googleapis.com/ajax/libs/jquery/1.7.1/jquery.min.js"></script>

<script type="text/javascript" src="includes/handlebars.js"></script>

<link rel="stylesheet" href="includes/codemirror.css">

<script src="includes/codemirror.js"></script>

<script src="includes/formatting.js"></script>

<script src="includes/xml.js"></script>

<script src="includes/javascript.js"></script>

<script src="includes/css.js"></script>

<script src="includes/htmlmixed.js"></script>

<script src="includes/jquery.cookie.js"></script>

<script src="uploadify/jquery.uploadify.v2.1.4.js"></script>

<script src="uploadify/swfobject.js"></script>

<script src="jqueryFileTree/jqueryFileTree.js"></script>

<script src="includes/keymaster.min.js"></script>

<script src="includes/md5.js"></script>

<script type="text/javascript" src="includes/script.js"></script>

<?php

        if(isset($_REQUEST['id'])){

            $id = $_REQUEST['id'];

            // get one row 

            $row = $db->fetchOneRow("SELECT fld_title,fld_html, fld_css, fld_js,fld_notes, fld_options FROM tbl_codebase WHERE id='$id' LIMIT 1");

            //echo $row->fld_html;

            $htmldecode = htmlspecialchars_decode($row->fld_html);

            $htmldecode = addslashes($htmldecode);

            $htmldecode = str_replace("\n",'\u000A',$htmldecode);

            $css = str_replace("\n",'\u000A',$row->fld_css);

            //$css = addslashes($css);

            $js = addslashes($row->fld_js);      

            $js = str_replace("\n",'\u000A',$js);

            

            $options = json_encode(explode(",", $row->fld_options));

            $notes = $row->fld_notes;

            

           // echo $row->fld_options;

?>

<script>

   $(window).load(function(){

        //var fileName = $('#codeid').val();

        $('#options').slideDown();

        $('#file_upload').uploadify({

            'uploader'  : 'uploadify/uploadify.swf',

            'script'    : 'uploadify/uploadify.php',

            'cancelImg' : 'uploadify/cancel.png',

            'folder'    : 'uploads',

            'auto'      : true,

            'fileExt'     : '*.zip',

            'scriptData'  : {'id':<?php echo $id ?>},

            'onComplete'  : function(event, ID, fileObj, response, data) {

                  //  alert('There are ' + data.fileCount + ' files remaining in the queue.');

                  window.clearTimeout(timeout);

                var timeout = window.setTimeout(function(){

                    fileTree(<?php echo $id ?>);

                }, 1000);

                

                

            }

        });

    })

    

    function fileTree(fileName){

            $('#filetree').fileTree({

                    root: 'uploads/'+fileName+'/',

                    script: 'jqueryFileTree.php',

                    expandSpeed: 1000,

                    collapseSpeed: 1000

                }, function(file) {

                    codebase.manageDependancy(file);

                });

        }

</script>

<?php

            echo "<script>

                    $(function(){

                        window.clearTimeout(timeoutfolder);

                        var timeoutfolder = window.setTimeout(function(){

                            var fileName = $('#codeid').val();

                            fileTree(fileName);

                        }, 1000);

                        codebase.resetAll();

                        $('#name').val('".$row->fld_title."');

                        var str = '".$htmldecode."';

                        var noteStr = '".$notes."';

                        var strReplace = str.replace(/xscript/g,'script');

                        var noteReplace = noteStr.replace(/xscript/g,'script');

                        codebase.Htmleditor.setValue(strReplace);

                        codebase.CssEditor.setValue('".$css."');

                        codebase.JsEditor.setValue('".$js."');

                        $.cookie('flag', 'update');

                        $('#codeid').val('".$id."');

                        $('#notes').val(noteReplace);

                        var opts = [".$options."];

                        $(opts[0]).each(function(i,item){

                            if(item !==''){

                                var getFileName = codebase.getDependancyFilename(item);

                                $('#dependencyFiles').append('<li data-url=\"'+item+'\">'+getFileName+'<span class=\"close\">x</span></li>');

                            }

                        })

                    })

                </script>";

            //echo $row->table;

        }else{

            echo "<script>$(function(){ $.cookie('flag', 'new'); codebase.newSnippet(); })</script>";

        }

    ?>

</head>



<body>

    

    <div class="header cf">

        

        <div id="ribbon">

	

	<div class="inset"></div>

	

	<div class="container">

		<div class="base">

                     <h1>Codebase</h1>

                </div>

	</div>



</div>

        <div class="buttons">

            <a href="#" class="btn save" id="save">Save</a>

            <a href="preview.php?id=<?php echo $id ?>" target="_blank" class="btn preview" id="preview">Preview</a>

            <a href="#" class="btn new" id="new">New</a>

            <a href="download.php?id=<?php echo $id ?>" class="btn download" id="download">Download</a>

            <a href="#" class="btn delete" id="delete">Delete</a>

        </div>

    </div>  

    <div class="container">

        <div class="name">

           <label>Name</label>

           <input type="text" id="name" value=""/> 

           <div class="autocomplete">

               <ul id="autoComplete">

                

                </ul>

           </div>

           <script id="codebase-autocomplete" type="text/x-handlebars-template">

                <li id="{{id}}">{{title}}</li>

            </script>

           <input type="hidden" id="codeid" name="codeid" value="" />

        </div>

        <div class="html-wrap">

            <label id="labelHTML">HTML</label>

            <textarea id="html"></textarea>  

        </div>

        <div class="css-wrap">

            <label>CSS</label>

            <textarea id="css"></textarea>

        </div>

         <div class="js-wrap">

            <label>Javascript</label>

            <textarea id="javascript"></textarea>

         </div>

         <div class="notes-wrap">

        <label>Notes</label>

        <textarea id="notes"></textarea>

        </div>

         

    </div>

    <div id="options">

    <h3>Dependency files</h3>

        <ul id="dependencyFiles">

        </ul>

        

        <h3>Related files </h3>

        <div id="filetree">

            

        </div>

        <h3 class="btn-select"><input id="file_upload" type="file" name="file_upload" /></h3>

    </div>

<!--    <div id="preview-box">

        

    </div>-->

<div id="login" class="nice">

	<div class="form-field">

            <div class="form">

                <div class="loginBox">

                    <h3>Login</h3>

                    <input name="txtUsername" id="txtUsername" type="text" placeholder="Username" class="input-text"  >

                    <input name="txtPassword" id="txtPassword" type="password" placeholder="Password" class="input-text"  >

                    <button id="btnLogin" class="btn">Login</button>

                    <div class="alert-box error">



                    </div>

                </div>

                <button id="btnLoginOut" class="btn">Login out</button>

            </div>

	</div>

        </div>

</body>

</html>