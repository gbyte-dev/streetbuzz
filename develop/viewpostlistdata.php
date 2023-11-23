
<?php
//This query for getting results from post day feel table where result is null.
$dbname="streetbu_sb_test";

//Connect to the database
$connection = mysqli_connect("localhost","streetbu_sb_test","streetbu_sb_test");
mysqli_select_db($connection,$dbname);
$id = $_GET['id'];
$viewquery  = "SELECT u.username,pvl.post_id,pvl.ip_addr,pvl.posted_time  FROM post_views_list as pvl 
inner join posts as p ON p.id =pvl.post_id 
left join users as u ON p.user_id =u.id WHERE pvl.id='$id'
group by pvl.post_id,pvl.ip_addr order by pvl.id desc ";
echo $viewquery;
$viewcheck    = mysqli_query($connection,$viewquery);
while($viewres = mysqli_fetch_assoc($viewcheck)){
    //print_r($viewres['username']);
 

$ip=$viewres['ip_addr'];
if($ip !=''){
           
               $response=file_get_contents('http://www.google.com');
print_r($response);
}
}

/*$data['data'] =array(array('a','bwr87wr','c','1234','12','320,800'),array('a','b','c','1234','dffi','$320,800'),array('a','b','c','1234','dffi','$320,800'),array('a','b','c','1234','dffi','$320,800'));
$as = json_encode($data);
echo $as;
*/

?>
