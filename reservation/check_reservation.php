<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header("Location: ../login/login2.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Reservation List</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../homepage/homepage2.css">
<link rel="stylesheet" href="../bg/bg.css">

<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>


</head>
<body>

<?php
require '../navbar.php';

$e_IDURL = $_GET['e_ID'];
?>



<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
<img src="../homepage/distedlogo.png" alt="Disted Logo" style= "height:20%; width:50%;"> 
</header>

<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <div class="check_reservation">
        <h1>List of Reservations</h1>
        <?php

        // Connect to the database
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        $db = mysqli_select_db($connection, 'event_management');
        //select the notifications from the database (where `s_ID` = null OR `s_ID` = {$_SESSION['s_ID']})
        $query_view_reservation = "SELECT reservation.* , student.s_name
        FROM reservation
        LEFT JOIN student ON reservation.s_ID = student.s_ID
        WHERE e_ID = $e_IDURL;";

        $query_view_reservation_run = mysqli_query($connection, $query_view_reservation);
        $count = 1;
        //if there are notifications
        if(mysqli_num_rows($query_view_reservation_run) > 0)
        {
            echo '<table width="150%" border="1" cellpadding="5" cellspacing="5">';
            echo '<tr><th>No</th><th>Student Name</th><th>Date</th><th>Time</th>';
            
            //print out all the notifications
            while($row = mysqli_fetch_assoc($query_view_reservation_run))
            {
              echo "<form method='POST' action=''>";
              echo '<tr>';
              echo '<td>'. $count .'</td>';
              echo "<td>". $row['s_name'] . "</td>";
              echo "<td>" . $row['r_date'] . "</td>";
              echo "<td>" . $row['r_time'] . "</td>";
              echo '</tr>';
              echo "</form>";
              $count++;
            }
          echo '</table>';
        }
        else
        {
          echo "<h1>There are no reservations</h1>";
        }
    ?>
      </div>
    </div>
  </div>
</div>