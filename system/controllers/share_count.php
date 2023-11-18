<?php 

$servername = "127.0.0.1";
$username = "streetbu_sb_test";
$password = "streetbu_sb_test";
$dbname = "streetbu_sb_test";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}

$id = $_GET['id'];
$sql = "SELECT share_count FROM posts WHERE id='$id'";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
  // output data of each row
  while($row = $result->fetch_assoc()) {
     $current = $row["share_count"];  
    
    $current++;
    
    $sql = "UPDATE posts SET share_count='$current' WHERE id='$id'";

if ($conn->query($sql) === TRUE) {
  echo "Record updated successfully";
} else {
  echo "Error updating record: " . $conn->error;
}
  }
} else {
  
}
$conn->close();

?>