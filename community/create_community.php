<?PHP
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header ("Location: ../login/login2.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Create Community</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../homepage/homepage2.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}

input{
    width: 50%;
    height: 5%;
    border: 1px;
    border-radius: 5px;
    padding: 8px 15px 8px 15px;
    margin: 10px 0px 15px 0px;
    box-shadow: 1px 1px 2px 1px grey;
    font-weight: bold;
}
</style>
</head>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class=" w3-black w3-card w3-left-align w3-large">

    <!-- <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="homepage2.php" class="w3-bar-item w3-button w3-padding-large w3-white">Home</a> -->
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
<!-- <h2>Welcome <?php echo $_SESSION['name']?></h2> -->
<!-- <h1 class="w3-margin w3-jumbo">CY T-SHIRT</h1>
  <p class="w3-xlarge">Buy your OWN Tees!</p> -->
<img src="../homepage/distedlogo.png" alt="Disted Logo" style= "height:20%; width:50%;"> 
</header>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <center>    
        <h1>Create Community</h1>
        <form action="create_community.php" method="POST" enctype="multipart/form-data">
            
            <label>Community name: </label><br>
            <input type="text" name="c_name" placeholder="Enter Community Name"/><br> 
            <label>Description: </label><br>
            <input type="text" name="c_description" placeholder="Enter Community Description"/><br> 
            <label>Community icon: (Max 64KB)</label><br>
            <input type="file" name="c_image" id="c_image" /><br> 
            <input type="submit" name="upload" value="Upload Data"/><br>
            <!-- <button> Upload data</button> -->
        </form>
    </center>
  </div>
</div>

<?php
//connect to database 
$connection = mysqli_connect('localhost','root','');
$db = mysqli_select_db($connection,'event_management');

//if the form is uploaded
if(isset($_POST['upload']))
{
    $name = $_POST['c_name'];
    $description = $_POST['c_description'];
    
    //if the connection is established 
    if($db)
    {
        //check if the community of the same name is in the database
        $query = "SELECT * FROM community WHERE c_name = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        if (strlen($name)==0)
        {
            echo '<script type="text/javascript"> alert("Please enter Community Name")</script>';

        }

        else if (strlen($description)==0)
        {
            echo '<script type="text/javascript"> alert("Please enter Community Description")</script>';

        }

        else if (mysqli_num_rows($result) > 0)
        {
            echo '<script type="text/javascript"> alert("Community Name already taken")</script>';
        }
        
        else
        {
          //check if the $_FILES is not empty 
          if (isset($_FILES['c_image']) && !empty($_FILES['c_image']['tmp_name'])) 
          {
            $file = addslashes(file_get_contents($_FILES["c_image"]["tmp_name"]));
            // upload the file
            $query = "INSERT INTO `community` (`c_name`,`c_image`,`c_description`) VALUES ('$name' , '$file' , '$description')";
            $query_run = mysqli_query($connection,$query);
            $c_id = mysqli_insert_id($connection);

            if($query_run)
            {
              echo '<script type="text/javascript"> alert("Community created successfully")</script>';
              //insert current user id and community id to admin table 
              $adminquery = "INSERT INTO `admin` (`c_ID`,`s_ID`) VALUES ('$c_id' , '{$_SESSION['s_ID']}' )";
              $adminquery_run = mysqli_query($connection, $adminquery);
              $joinquery = "INSERT INTO `joins` (`s_ID` , `c_ID`) VALUES ('{$_SESSION['s_ID']}' , '$c_id')";
              $joinquery_run = mysqli_query($connection, $joinquery);
            }
          } 
          
          else
          {
            // handle the case where no file was uploaded
            echo '<script type="text/javascript"> alert("Image Profile Not Uploaded")</script>';
          }

        }

    }

}
//require '../footer/footer2.html';
?>

</body>
</html>


	</body>
	</html>
