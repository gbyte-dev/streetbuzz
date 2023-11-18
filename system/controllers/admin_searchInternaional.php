<?php
 

if(strlen($_REQUEST["query"]) > 2) {
        $output = '';  
        
        $query = "SELECT * FROM users WHERE username LIKE '%".$_REQUEST["query"]."%'";  
        $sql=  $db2->query($query);
        
           
 
       $output = '<ul class="list-unstyled">';  
        if($sql->num_rows > 0){  
            while($row = $sql->fetch_assoc()){ 
                $uname = $row["username"]; // Removing unnecessary quotes around $row["username"]

$output .= '<li><a onclick="readInternaional_byid(' . $row["id"] . ', \'' . $uname . '\')">' . $row["username"] . '</a></li>';
 
            }  
        }else{  
            $output .= '<li>User Not Found</li>';  
        }  
    
    $output .= '</ul>';  
    echo $output;  


}
 







//}
?>