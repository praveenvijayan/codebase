<?php

    include_once('config.php');
	
	//phpinfo();

    if(isset($_REQUEST['q'])){

        $result =  $_REQUEST['q'];
		
		//echo $result;
		
        $root = explode(":", $result);
		$root = $root[0];

        $tojson = array();

				

        if($root == 'html' || $root == 'h'){

            $result = str_replace($root.':','', $result);

            $result = strtoupper($result);

            $result = strip_tags($result); 

            $result = trim ($result);

			

            if($result != ""){

                $query = "SELECT * FROM tbl_codebase WHERE fld_title LIKE '%$result%' LIMIT 10";

                foreach ($db->iterate($query) as $row) {

                    $htmlDecoded = htmlspecialchars_decode($row->fld_html);

		    $htmlDecoded = str_replace("xscript","script",$htmlDecoded);

                    if(!empty($htmlDecoded)){

                        array_push($tojson, array('html'=> $htmlDecoded,'title'=>$row->fld_title,'id'=>$row->id));

                    }

                }

                echo json_encode($tojson);   

            }

        }

         

        if($root == 'css' || $root == 'c'){

            $result = str_replace($root.':','', $result);

            $result = strtoupper($result);

            $result = strip_tags($result); 

            $result = trim ($result);

            $query = "SELECT * FROM tbl_codebase WHERE fld_title LIKE '%$result%' LIMIT 10";

            foreach ($db->iterate($query) as $row) {

                if(!empty($row->fld_css)){

                    array_push($tojson, array('css'=> $row->fld_css,'title'=>$row->fld_title,'id'=>$row->id));

                }

            }

            echo json_encode($tojson);

        }

        

        

        if($root == 'js' || $root == 'j'){

            $result = str_replace($root.':','', $result);

            $result = strtoupper($result);

            $result = strip_tags($result); 

            $result = trim ($result);

            $query = "SELECT * FROM tbl_codebase WHERE fld_title LIKE '%$result%' LIMIT 10";

            foreach ($db->iterate($query) as $row) {

              if(!empty($row->fld_js)){  

                array_push($tojson, array('js'=> $row->fld_js,'title'=>$row->fld_title,'id'=>$row->id));

              }

            }   

            echo json_encode($tojson);           

        }

        

        if($root == 'full' || $root == 'f'){

            $result = str_replace($root.':','', $result);

            $result = strtoupper($result);

            $result = strip_tags($result); 

            $result = trim ($result);

            $query = "SELECT * FROM tbl_codebase WHERE fld_title LIKE '%$result%' LIMIT 10";

            foreach ($db->iterate($query) as $row) {

              if(!empty($row->fld_title)){  

                $htmlDecoded = htmlspecialchars_decode($row->fld_html);

		$htmlDecoded = str_replace("xscript","script",$htmlDecoded);

                array_push($tojson, array('html'=> $htmlDecoded, 'css'=> $row->fld_css, 'js'=> $row->fld_js,'title'=>$row->fld_title,'id'=>$row->id));

              }

            }   

            echo json_encode($tojson);           

        }

		

		if(is_numeric($root)){

			$result = str_replace($root.':','', $result);

			//$result = strtoupper($result);

            $result = strip_tags($result); 

            $result = trim ($result);

						

			if($result == 'html'){

				$row = $db->fetchOneRow("SELECT fld_html FROM tbl_codebase WHERE id='$root' LIMIT 1");

				$htmldecode = htmlspecialchars_decode($row->fld_html);

				echo json_encode(array("html"=>$htmldecode));       

			}

			

			if($result == 'css'){

				$row = $db->fetchOneRow("SELECT fld_css FROM tbl_codebase WHERE id='$root' LIMIT 1");

				echo json_encode(array("css"=>$row->fld_css));  

			}

			

			if($result == 'js'){

				$row = $db->fetchOneRow("SELECT fld_js FROM tbl_codebase WHERE id='$root' LIMIT 1");

				echo json_encode(array("js"=>$row->fld_js));  

			}

			

		}

        

       // echo json_encode($arr);

        

    }

?>

