<?php
 
 
        $output = '';  
        $query = "SELECT * FROM users WHERE id =".$_POST["id"]."";  
        $sql=  $db2->query($query);
         if($sql->num_rows > 0){  
           $row = $sql->fetch_assoc(); 
           $data_user = array(
               'username' =>$row['username'],
               'email' =>$row['email'],
               'phone_no' =>$row['phone_no'],
               );
             echo json_encode($data_user);
            }else{  
                        $data_user = array();
                          echo json_encode($data_user);
    
            }  
        
         echo $output;  

 
?>