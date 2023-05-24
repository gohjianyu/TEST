<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header("Location: ../login/login2.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Community Page</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../homepage/homepage2.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>
/* CSS styles for the community page */
/* Customize the styles as per your requirements */
</style>
</head>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class="w3-black w3-card w3-left-align w3-large">

    <div class="navbar">
      <a href="../homepage/homepage2.php">Home</a>
      <a href="../community/create_community.php">Create Community</a>
      <div class="dropdown">
  <button class="dropbtn">My Communities 
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-content">
    <?php

    require '../configure.php';
    // Connect to the database
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
    $db = mysqli_select_db($connection, 'event_management');

    // Query to retrieve communities joined by the current student
    $query = "SELECT community.c_name, community.c_ID
              FROM community
              INNER JOIN joins ON community.c_ID = joins.c_ID
              WHERE joins.s_ID = '{$_SESSION['s_ID']}'";
    
    $query_run = mysqli_query($connection, $query);
    // Iterate over the retrieved communities and display them as dropdown options
    while ($row = mysqli_fetch_array($query_run)) {
      $c_ID = $row['c_ID'];
      echo  '<a href="../community/community_page.php?c_ID=' . $c_ID . '">' . $row['c_name'] . '</a>';
    }
    ?>
  </div>
</div>
  <a href="#">My Account</a>
  <a href="../login/logout.php">Logout</a>
</div>
    <!-- <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">My Account</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">My Order</a> -->
</div>
</div>

<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
  <img src="../homepage/distedlogo.png" alt="Disted Logo" style="height:20%; width:50%;"> 
</header>

<!-- Community Content -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <div class="communityStuff">

    <?php
      // Connect to the database
      $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
      $db = mysqli_select_db($connection, 'event_management');

      // Retrieve the community information based on the community ID
      $c_ID = $_GET['c_ID']; // Assuming you pass the community ID in the URL
      $query = "SELECT * FROM `community` WHERE `c_ID` = '$c_ID'";
      $query_run = mysqli_query($connection, $query);

      // Check if the community exists
      if (mysqli_num_rows($query_run) > 0) {
        $row = mysqli_fetch_assoc($query_run);
        $c_name = $row['c_name'];
        $c_description = $row['c_description'];
        $c_image = $row['c_image'];

        // Display the community information
        echo '<h1>' . $c_name . '</h1>';
        echo '<img src="data:image;base64,' . base64_encode($c_image) . '" alt="Community Image" style="width: 100px; height: 100px;">';
        echo '<p>' . $c_description . '</p>';
        
        $queryjoin = "SELECT * FROM joins WHERE s_ID='{$_SESSION["s_ID"]}' AND c_ID= $c_ID";
        $query_runjoin = mysqli_query($connection,$queryjoin);
        if(mysqli_num_rows($query_runjoin)>0)
        {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='community_id' value='" . $c_ID . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<input type='submit' name='leave' value='Leave' />";
            echo "</form>";
        }
        else
        {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='community_id' value='" . $c_ID . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<input type='submit' name='join' value='Join' />";
            echo "</form>";
        }
    }

    else 
    {
        echo '<p>Community not found.</p>';
    }

    if (isset($_POST["join"])) 
    {
    $student_id = $_POST["student_id"];
    //check if the user is already in the community
    $sql = "SELECT * FROM joins WHERE s_ID = '$student_id' AND c_ID = '$c_ID'";
    $result = mysqli_query($connection, $sql);
        if(mysqli_num_rows($result) > 0)
        {
            // record already exists
            echo "<script>alert('You have already joined this community.');</script>";
        } 
        
        else 
        {
            // insert the record
            $sql = "INSERT INTO joins (s_ID, c_ID) VALUES ('$student_id','$c_ID')";
                if(mysqli_query($connection, $sql))
                {
                    // join successful
                    echo "<script>alert('You have successfully joined the community.');</script>";
                } 
        
                else 
                {
                    // join failed
                    echo "<script>alert('Failed to join the community. Please try again.');</script>";
                }
        }
    }
    if(isset($_POST["leave"]))
    {
        $dltquery = "SELECT * FROM joins WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = '$c_ID'";
        $deltqueryresult = mysqli_query($connection, $dltquery);
        if(mysqli_num_rows($deltqueryresult) == 0)
        {
            
            echo "<script>alert('You have already left this community.');</script>";
        } 
        else
        {
            $querydlt = "DELETE FROM `joins` WHERE s_ID =  {$_SESSION["s_ID"]} AND c_ID = $c_ID";
            $query_rundlt = mysqli_query($connection, $querydlt);
            if($query_rundlt)
            {
                echo "<script>alert('Leaved Successfully.');</script>";
            }
            

        } 
    }
      ?>
      </div>
      
      <div class="eventStuff">
      
      </div>

    </div>
   </div>
</div>

<!-- Footer -->
<footer>
  <!-- Footer content -->
</footer>

</body>
</html>