<?php
$servername = "dedi675.jnb1.host-h.net";
$username = "EohSalesCon";
$password = "ng9vxgR8";
$dbname = "Eoh_Sales_Conf";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$Username =	$_POST['Username'] ;
$MorningLearning = $_POST['morninglearning'] ;
$MorningStrategy =$_POST['name'];
$Afternoon_Learning = $_POST['name'];
$Afternoon_Strategy= $_POST['name'];
 $query = "Checkin (Username, Morning_Learning, Morning_Strategy,Afternoon_Learning,Afternoon_Strategy)
VALUES ($Username, $MorningLearning, $MorningStrategy, $Afternoon_Learning, $Afternoon_Strategy)";

  $result = mysqli_query($con,$query);

  $rows = array();
  while($r = mysqli_fetch_array($result)) {
    $rows[] = $r;
  }
  echo json_encode($rows);




$conn->close();
?> 