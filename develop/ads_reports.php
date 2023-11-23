
<div style="width:100%">
    <div style="width:50%">
<a href="https://streetbuzz.co.in/newsapp/home" title="StreetBuzz" class="system-logo"><img src="https://streetbuzz.co.in/newsapp/static/images/logo.jpg" alt="StreetBuzz" width="40"><img src="https://streetbuzz.co.in/newsapp/static/images/logo.png" alt="StreetBuzz"></a>
<div>Local|Real-time|Interactive News</div>
</div>




<div style="width:40%;float:right">
    Customer Name:<?php if(!empty($results)){
    echo $results[0]["customer_name"] ;
        
    }
    ?>
</div>
<br />

<div style="width:40%;float:right">
    Contact Number:<?php if(!empty($results)){ echo $results[0]["contact_number"]; } ?>
</div>
<br />
<div style="width:40%;float:right">
Sales Person:<?php if(!empty($results)){ echo $results[0]["sales_person"];}?>
</div>
<br />


<br />
<div width="100%">
<table>
  <tr>
      <th>Sno.</th>
    <th>Date</th>
    <th>No.of Views</th>
    <th>No.of Clicks</th> 
  
  </tr>
  <?php if(!empty($results)){ 
      foreach($results as $keys=>$vals){
      
      ?>
  <tr>
     <td><?php echo $keys++;?></td>
    <td><?php echo $vals["view_date"];?></td>
    <td><?php echo $vals["cnt"]?></td>
    <td><?php echo $vals["clickcnt"]?></td>
   
  
  </tr>
  <?php }} ?>
  
</table>

</div>
<style>
<style>
table {
  width:100%;
}
table, th, td {
  border: 1px solid black;
  border-collapse: collapse;
}
th, td {
  padding: 15px;
  text-align: left;
}
</style>
