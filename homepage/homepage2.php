<?PHP
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header ("Location: ../login/login2.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Homepage</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="homepage2.css">
<link rel="stylesheet" href="../bg/bg.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}

#communities{
    text-align: center;
    margin-top: 50px;
    margin-bottom: 50px;
  }

  #calendar{
    text-align: center;
  }

  input[type=submit]
  {
    width: 80%;
  }
  
</style>
</head>
<body>

<?php
require '../navbar.php';
?>

<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
<img src="distedlogo.png" alt="Disted Logo" style= "height:20%; width:50%;"> 
</header>

<?php

//if the form is posted
if (isset($_POST["join"])) 
{
$student_id = $_POST["student_id"];
$community_id = $_POST["community_id"];
//check if the user is already in the community
$sql = "SELECT * FROM joins WHERE s_ID = '$student_id' AND c_ID = '$community_id'";
$result = mysqli_query($connection, $sql);
if(mysqli_num_rows($result) > 0)
{
    // record already exists
    echo "<script>alert('You have already joined this community.');</script>";
} 

else 
{
    // insert the record
    $sql = "INSERT INTO joins (`s_ID`, `c_ID`,`role`) VALUES ('$student_id','$community_id','member')";
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

if (isset($_POST["view"]))
{
  $community_id = $_POST['community_id'];
  header ("Location: ../community/community_page.php?c_ID=".$community_id);
} 
?>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="">
      <h1 id = 'calendar'>Calendar</h1>
      <?php
      require 'calendar.php'
      ?>

      <h1 id = 'communities'>Communities</h1> 

      <center>
    <form action="" method="POST">
        <table width="100%" border="1" cellpadding="5" cellspacing="5">
            <thead>
                <tr>
                    <th style="width:10%">Icon</th>
                    <th style="width:30%">Community Name</th>
                    <th style="width:50%">Description</th>
                    <th style="width:10%">View & Join</th>
                </tr>
            </thead>
            <?php
            //connect to database 
            $connection= mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
            $db = mysqli_select_db($connection,'event_management');

            //select all the records in the table `community`
            $query = "SELECT * FROM `community` ";
            $query_run = mysqli_query($connection,$query);

            $i=1;
            //while it has data, print it out 
            while($row = mysqli_fetch_array($query_run))
            {
                ?>
                <tr>
                    <td> <?php echo '<img src="data:image;base64,'.base64_encode($row['c_image']).' "alt="image" style="width: 100px; height: 100px;">';?></td>
                    <td> <?php echo $row['c_name']?></td>
                    <td> <?php echo $row['c_description']?></td>
                    <td>
                    <?php 
                        //select the community that the current user joins
                        $query2 = "SELECT * FROM joins WHERE s_ID='{$_SESSION["s_ID"]}' AND c_ID='{$row["c_ID"]}'";
                        $query_run2 = mysqli_query($connection,$query2);
                        //if there's results returned (meaning the user has already joined) then disable the button
                        if(mysqli_num_rows($query_run2)>0)
                        {
                          echo "<button disabled>Joined</button>";
                          echo "<form method='POST'>";
                          echo "<input type='hidden' name='community_id' value='" . $row["c_ID"] . "' />";
                          echo "<input type='submit' name='view' id='view' value='View' />";
                          echo "</form>";
                        }
                        else
                        {
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='community_id' value='" . $row["c_ID"] . "' />";
                        echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
                        echo "<input type='submit' name='join' id='join' value='Join' />";
                        echo "<input type='submit' name='view' id='view' value='View' />";
                        echo "</form>";
                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </form>


</center>

  </div>
</div>

<!-- <?php
require '../footer/footer2.html';
?> -->

<!-- <script >


</script> -->
</body>
</html>


	</body>
	</html>
