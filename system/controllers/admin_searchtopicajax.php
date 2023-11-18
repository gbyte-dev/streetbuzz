<?php
error_reporting(E_ALL);

ini_set('upload_max_filesize', '200M');
ini_set('post_max_size', '200M');                               
ini_set('max_input_time', 3000);                                
ini_set('max_execution_time', 3000);
	if( !$this->network->id ) {
		$this->redirect('home');
	}
	if( !$this->user->is_logged ) {
		$this->redirect('signin');
	}
	global $db2;
	$newarr = [];
	$topic = $_POST['topic'];
	$querystr ='';
	if($topic != '' || $topic != null ){
	 $newarr['topic_literal'] ="LOWER(topic_literal) like  '%$topic%' ";
	 
	}
	$description = $_POST['description'];
	if($description != '' || $description != null ){
	    //$newarr['topic_description'] = $description;
	    $newarr['topic_description'] ="LOWER(topic_description) like  '%$description%' ";

	}
    $topic_category = $_POST['topic_category'];
    if($topic_category != '' || $topic_category != null ){
	   // $newarr['topic_category'] = $topic_category;
	    $newarr['$topic_category'] ="topic_category = '%$topic_category%' ";
	}
    $topic_location = $_POST['topic_location'];
    if($topic_location !=''){
        //$newarr['topic_location'] = $topic_location;
         $newarr['topic_location'] ="LOWER(topic_attach_location) like  '%$topic_location%' ";

    }
    $topic_language = $_POST['topic_language'];
    if($topic_language  !=''){
        //$newarr['topic_language'] = $topic_language;
         $newarr['topic_language'] ="LOWER(topic_attach_language) like  '%$topic_language%' ";
    }
    $start_date = $_POST['start_date'];
    $end_date = $_POST['end_date'];

    if($start_date  !=''){
        $start_date = strtotime($start_date.'23:59:00');;
        $newarr['start_date'] = "valid_from like '$start_date' ";

    }
    if($end_date != ""){
        $end_date =strtotime($end_date.'23:59:00');;
        $newarr['end_date'] = "valid_till like '$end_date' ";
    }
    $condition = '';
    if(count($newarr) > 0){
        $m = 0;
    foreach($newarr as $keys=>$vals){
        if($m == (count($newarr) -1 )){
         $orcondition ='';   
        }else{
            $orcondition =' OR ';
        }
        $str = $vals.$orcondition;
       $condition .= $str; 
       $m++;

       } 
    }


   $categeory_masterquery        = $db2->query("select id,topic_literal from sb_topics WHERE $condition");
 $data =[];
 $html = '';
 $html .= '<div><h5>Choose One Topic</h5></div>';

      while($result    = $db2->fetch_object($categeory_masterquery)){ 

  $html .= '<div><a href="'.$C->SITE_URL.'admin/edittopics?postid='.$result->id.'" class="searcha">'.$result->topic_literal.'</a></div>';
     
  }
   $html .='<style>.searcha{border:1px solid orange;padding: 5px;
    border-radius: 14px;
    background-color: gray;
    color: white;padding-left:10px;padding-right:10px"}
    .searcha:hover{border:1px solid orange;padding: 5px;
    border-radius: 14px;
    background-color: gray;
    color: white !important;padding-left:10px;padding-right:10px"}
    </style>
    ';

 $data['status'] = 200;
 $data['response'] = $html;
 echo json_encode($data);
  


?>