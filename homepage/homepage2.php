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
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}

        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        .button1 {background-color: #008CBA;} 
        .button2 {background-color: #4CAF50;} 
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
<img src="distedlogo.png" alt="Disted Logo" style= "height:20%; width:50%;"> 
</header>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <h1>Calendar</h1>

      <h1>Communities</h1> 

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
                          echo "<a href = '../community/community_page.php?c_ID=".$row['c_ID']."'> View </a>";
                        }
                        else
                        {
                        echo "<form method='POST'>";
                        echo "<input type='hidden' name='community_id' value='" . $row["c_ID"] . "' />";
                        echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
                        echo "<input type='submit' name='join' value='Join' />";
                        echo "</form>";
                        echo "<a href = '../community/community_page.php?c_ID=".$row['c_ID']."'> View </a>";

                        }
                        ?>
                    </td>
                </tr>
                <?php
            }
            ?>
        </table>
    </form>

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
        $sql = "INSERT INTO joins (s_ID, c_ID) VALUES ('$student_id','$community_id')";
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
    ?>
</center>

    </div>  
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
