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
body {
  font-family: "Lato", sans-serif;
}

.w3-top {
  position: fixed;
  width: 100%;
  z-index: 1;
}

.w3-black {
  background-color: #000 !important;
}

.navbar {
  display: flex;
  align-items: center;
}

.navbar a {
  color: #fff;
  padding: 14px 16px;
  text-decoration: none;
  font-size: 18px;
}

.dropdown {
  position: relative;
  display: inline-block;
}

.dropdown-content {
  display: none;
  position: absolute;
  background-color: #f9f9f9;
  min-width: 160px;
  box-shadow: 0px 8px 16px 0px rgba(0,0,0,0.2);
  z-index: 1;
}

.dropdown-content a {
  color: black;
  padding: 12px 16px;
  text-decoration: none;
  display: block;
}

.dropdown:hover .dropdown-content {
  display: block;
}

.w3-container {
  padding: 128px 16px;
}

.community-image {
  width: 100px;
  height: 100px;
}

.w3-twothird {
  margin-left: 25%;
}

h1 {
  font-family: "Montserrat", sans-serif;
  font-size: 36px;
  margin-bottom: 16px;
}

p {
  font-size: 18px;
  margin-top: 0;
}

footer {
  /* Customize the footer styles as per your requirements */
}
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
          $query = "SELECT community.c_name
          FROM community
          INNER JOIN joins ON community.c_ID = joins.c_ID
          WHERE joins.s_ID = '{$_SESSION['s_ID']}'";
          $query_run = mysqli_query($connection, $query);

          // Iterate over the retrieved communities and display them as dropdown options
          while ($row = mysqli_fetch_array($query_run)) {
            echo '<a href="#">' . $row['c_name'] . '</a>';
          }
          ?>
        </div>
      </div> 
      <a href="#">My Account</a>
      <a href="../login/logout.php">Logout</a>
    </div>
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
        echo '<p>' . $c_description . '</p>';
        echo '<img src="data:image;base64,' . base64_encode($c_image) . '" alt="Community Image" class="community-image">';
      } else {
        echo '<p>Community not found.</p>';
      }
      ?>
    </div>
  </div>
</div>

<!-- Footer -->
<footer>
  <!-- Footer content -->
</footer>

</body>
</html>