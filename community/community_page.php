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

<?php
require '../navbar.php';
?>

<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
  <img src="../homepage/distedlogo.png" alt="Disted Logo" style="height:20%; width:50%;"> 
</header>
<?php
$c_IDURL = $_GET['c_ID']; // Assuming you pass the community ID in the URL
//if leave button is pressed
if(isset($_POST["leave"]))
{
    //check if the student is in this community
    $dltquery = "SELECT * FROM joins WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = '$c_IDURL'";
    $deltqueryresult = mysqli_query($connection, $dltquery);
    //counts the number of admins in this community 
    $query_count = "SELECT COUNT(*)AS count FROM `joins` WHERE role = 'admin' AND c_ID = $c_IDURL";
    $query_count_run = mysqli_query($connection, $query_count);
    //selects the admins in this community 
    $query_is_admin = "SELECT * FROM `joins` WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = $c_IDURL AND role = 'admin'";
    $query_is_admin_run = mysqli_query($connection, $query_is_admin);
    //check if there is less then one admin in this community 
      if((mysqli_num_rows($query_count_run) <= 1 )&& (mysqli_num_rows($query_is_admin_run)))
      {
         echo "<script>alert('YOU SHALL NOT LEAVE.');</script>";    
      }
      else
      {
        //student can leave this community
        $querydlt = "DELETE FROM `joins` WHERE s_ID =  {$_SESSION["s_ID"]} AND c_ID = $c_IDURL";
        $query_rundlt = mysqli_query($connection, $querydlt);
        if($query_rundlt)
        { 
          echo "<script>alert('Left Successfully.');</script>";
        }
      }
  } 
  //if the join button is pressed
    if (isset($_POST["join"])) 
    {
    $student_id = $_POST["student_id"];
    //check if the user is already in the community
    $sql = "SELECT * FROM joins WHERE s_ID = '$student_id' AND c_ID = '$c_IDURL'";
    $result = mysqli_query($connection, $sql);
    // insert the record
    $sql = "INSERT INTO joins (`s_ID`, `c_ID`, `role`) VALUES ('$student_id','$c_IDURL','member')";
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

    //when the post event button is pressed go to the post event page
    if (isset($_POST["post_event"]))
    {
      header ("Location: ../event_/post_event.php?c_ID=".$c_IDURL);
    } 

    //check if the user has pressed the reserve event button
    if (isset($_POST["reserve"])) 
    {
    date_default_timezone_set('Asia/Kuala_Lumpur');
    $currentDate = date('Y-m-d');  // Format: YYYY-MM-DD
    $currentTime = date('H:i:s');  // Format: HH:MM:SS
    $e_ID = $_POST["event_ID"];
    $student_id = $_POST["student_id"];
    //check if the user is already in the community
    $sql = "SELECT * FROM reservation WHERE s_ID = '$student_id' AND e_ID = '$e_ID'";
    $result = mysqli_query($connection, $sql);
    if(mysqli_num_rows($result) > 0)
    {
        // record already exists
        echo "<script>alert('You have already made a reservation for this event.');</script>";
    } 

    else 
    {
        // insert the record
        $sql = "INSERT INTO reservation (`r_date`, `r_time`,`s_ID` , `e_ID`) VALUES ('$currentDate','$currentTime','$student_id' , '$e_ID')";
        if(mysqli_query($connection, $sql))
        {
            // join successful
            echo "<script>alert('You have successfully made a reservation for this event.');</script>";
        } 

        else 
        {
            // join failed
            echo "<script>alert('Failed to make a reservation. Please try again.');</script>";
        }
    }
    }

    
    //checks if the user has pressed cancel reservation button
    if (isset($_POST["cancel_reserve"])) 
    {
      $e_ID = $_POST["event_ID"];
      $student_id = $_POST["student_id"];
      //delete the record from the reservation table
      $querydlt_reserve = "DELETE FROM `reservation` WHERE s_ID =  $student_id AND e_ID = $e_ID";
      $query_rundlt_reserve = mysqli_query($connection, $querydlt_reserve);
      
      if($query_rundlt_reserve)
      {
        echo "<script>alert('Your reservation for this event has been canceled');</script>";
      }
      else 
      {
        // cancel failed
        echo "<script>alert('Failed to cancel the reservation. Please try again.');</script>";
      }
    }
    
    //edit event
    if (isset($_POST["edit_event"]))
    {
      $e_ID = $_POST["event_ID"];
      header ("Location: ../event_/edit_event.php?e_ID=".$e_ID."&c_ID=".$c_IDURL);
    } 

    //member list
    if (isset($_POST["member_list"]))
    {
      header ("Location: ../member_list/member_list_test.php?c_ID=".$c_IDURL);
    } 
    //cancel event
    if (isset($_POST["cancel_event"])) 
    {
      $e_ID = $_POST["event_ID"];
      //confirmation for cancel event
      echo '<script>  
      var result = confirm("Are you sure you want to cancel this event?");
      if (result)
      {
        // If the user confirms, redirect to the delete page
        window.location.href = "../event_/cancel_event.php?e_ID=' . $e_ID .'&c_ID='.$c_IDURL.'";
      }
      </script>';
    }
?>

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
      $query = "SELECT * FROM `community` WHERE `c_ID` = '$c_IDURL'";
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
        
        //check if the student has joined this community
        $queryjoin = "SELECT * FROM joins WHERE s_ID='{$_SESSION["s_ID"]}' AND c_ID= $c_IDURL";
        $query_runjoin = mysqli_query($connection,$queryjoin);
        //if joined
        if(mysqli_num_rows($query_runjoin)>0)
        {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='community_id' value='" . $c_IDURL . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<input type='submit' name='leave' value='Leave' />";
            echo "</form>";
        }
        else
        {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='community_id' value='" . $c_IDURL . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<input type='submit' name='join' value='Join' />";
            echo "</form>";
        }
    }

    else 
    {
        echo '<p>Community not found.</p>';
    }

    
      ?>
      </div>
      
      <div class="eventStuff">
      <?php
      //check if the user is an admin of the community 
      $querypost = "SELECT * FROM `joins` WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = $c_IDURL AND role = 'admin'";
      $querypostrun = mysqli_query($connection, $querypost);
      //check if the user is an admin or committee of the community
      $querypost2 = "SELECT * FROM `joins` WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = $c_IDURL AND (role = 'admin' OR role = 'committee')";
      $querypostrun2 = mysqli_query($connection, $querypost2);
      //if the user is an admin
      if(mysqli_num_rows($querypostrun) > 0)
      {
        echo "<form method='POST' action=''>";
        echo "<td><input type='submit' name='post_event' value='Post Event' /> </td>";
        echo "<td><input type='submit' name='member_list' value='Member List' /> </td>";
        echo "</form>";
      }
      //select the event information in this community
      $query_view_event= "SELECT `e_ID`,`e_name`, `e_description`, `e_venue`, `e_date_start`, `e_date_end`, `e_time` FROM event_ WHERE c_ID = $c_IDURL ORDER BY `event_`.`e_ID` DESC";
      $query_view_event_run = mysqli_query($connection, $query_view_event);
      $query_role = "";
      //if there is event posted in this community
      if(mysqli_num_rows($query_view_event_run) > 0)
      {
        echo '<table width="150%" border="1" cellpadding="5" cellspacing="5">';
        echo '<tr><th>Event Name</th><th>Event Description</th><th>Event Venue</th><th>Event Start Date</th><th>Event End Date</th><th>Event Time</th><th>Action</th>';
        
        //if the user is an admin or committee
        if(mysqli_num_rows($querypostrun2) > 0)
        {
          echo '<th>Action</th><th>Action</th>';
        }
        echo '</tr>';
        
        //print out all the event in this comminity
        while($row = mysqli_fetch_assoc($query_view_event_run))
        {
          echo '<tr>';
          echo "<td>" . $row['e_name'] . "</td>";
          echo "<td>" . $row['e_description'] . "</td>";
          echo "<td>" . $row['e_venue'] . "</td>";
          echo "<td>" . $row['e_date_start'] . "</td>";
          echo "<td>" . $row['e_date_end'] . "</td>";
          echo "<td>" . $row['e_time'] . "</td>";
          
          //check if the user has reserved the event
          $query_reserve = "SELECT * FROM `reservation` WHERE s_ID = '{$_SESSION["s_ID"]}' AND e_ID = '{$row["e_ID"]}'";
          $query_reserve_run = mysqli_query($connection, $query_reserve);

          //if the user has reserved the event
          if(mysqli_num_rows($query_reserve_run)>0)
          {
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<td><input type='submit' name='cancel_reserve' value='Cancel reservation' /> </td>";
            echo "</form>";
          }
          else{
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<td><input type='submit' name='reserve' value='Reserve Event' /> </td>";
            echo "</form>";
          }
          //if the user is an admin or commitee in this community
          if(mysqli_num_rows($querypostrun2) > 0)
          {
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<td><input type='submit' name='edit_event' value='Edit' /> </td>";
            echo "<td><input type='submit' name='cancel_event' value='Cancel event' /> </td>";
            echo "</form>";
          }

          echo '</tr>';
          
        }
        
        echo '</table>';
      }
      
      
      ?>
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

