<style>
input[type="checkbox"][id^="myCheckbox"] {
  display: none;
}

label {
  border: 1px solid #fff;
  display: block;
  position: relative;
  padding-top:13px;
  cursor: pointer;
}

label:before {
  background-color: white;
  color: white;
  content: " ";
  display: block;
  border-radius: 50%;
  border: 1px solid grey;
  position: absolute;
  top: -5px;
  left: -5px;
  width: 25px;
  height: 25px;
  text-align: center;
  line-height: 28px;
  transition-duration: 0.4s;
  transform: scale(0);
}

label img {
  height: 100px;
  width: 100px;
  transition-duration: 0.2s;
  transform-origin: 50% 50%;
}

:checked + label {
  border-color: #ddd;
}

:checked + label:before {
  content: "✓";
  background-color: grey;
  transform: scale(1);
}

:checked + label img {
  transform: scale(0.9);
  /* box-shadow: 0 0 5px #333; */
  z-index: -1;
}

</style>

<?php

        $output = '';  
 
       $query = "SELECT * FROM sb_location_master WHERE state_id =" .$_REQUEST["stateid"]; 

        $sql=  $db2->query($query);

 
 
 
 
        
                
    
          /*      <span href="https://streetbuzz.co.in/newsapp/home?st=1&amp;name=Uttar Pradesh">
        <span class="mt-2" style="font-size: small; color: #2c6b82;">Uttar Pradesh - उत्तर प्रदेश</span>
        </span>
        */
 
       $query1 = "SELECT * FROM state WHERE id =" .$_REQUEST["stateid"]; 

        $sql1=  $db2->query($query1);
        $row1 = $sql1->fetch_assoc();
        $state_name =  $row1['name'];
       

        
       $output = '<div class="col-sm-12 mb-2"><h3 style="text-align: center;font-size: 18px;">Select one or more location with '.$state_name.'</h3></div>';  
       
 

        if($sql->num_rows > 0){  
            while($row = $sql->fetch_assoc()){ 

if($row["fileName"] == ""){
    $output .= '<div class="col-sm-4 mb-2 mx-1" style="border:1px solid #c3c2c2;max-width:31% !important;">
 <input type="checkbox" id="myCheckbox'.$row["id"].'" class="ids" value="'.$row["id"].'" name="locationids[]"/>
    <label for="myCheckbox'.$row["id"].'"><img style="width:100%;height:100px;border-radius: 10px;cursor:pointer;" alt="Image3" src="https://streetbuzz.co.in/newsapp/static/images/streetbuzz.jpg">
<h6 style="font-size: small; color: #2c6b82;text-align:center;">'.$row["location"].'</h6></label>

</div>


';
    
}else {
$output .= '<div class="col-sm-4 mb-2 mx-1" style="border:1px solid #c3c2c2;max-width:31% !important;">
<input type="checkbox" id="myCheckbox'.$row["id"].'" class="ids"/>
    <label for="myCheckbox'.$row["id"].'"><img style="width:100%;height:100px;border-radius: 10px;cursor:pointer;" alt="Image3" src="https://streetbuzz.co.in/newsapp/assets/images/' . $row["fileName"] . '">
<h6 style="font-size: small; color: #2c6b82;text-align:center;">'.$row["location"].' </h6></label>


</div>

';
}
 
            }  
        }else{  
            $output .= '<li>District Not Found</li>';  
        }  
    
    $output .= '';  
    echo $output;  
?>

