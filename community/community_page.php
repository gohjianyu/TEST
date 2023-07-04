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
<link rel="stylesheet" href="community.css">
<link rel="stylesheet" href="../bg/bg.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>

</style>
</head>
<body>

<?php
require '../navbar.php';

if (isset($_GET['feedback']) && $_GET['feedback'] === "success") {
    // Get the current URL
    $currentUrl = $_SERVER['REQUEST_URI'];
  
    // Remove the '&feedback=success' from the URL
    $modifiedUrl = str_replace('&feedback=success', '', $currentUrl);
    
    // Print the JavaScript code to show the alert and redirect after a delay
    echo '
      <script type="text/javascript">
        alert("Thank You For Your Feedback");
        setTimeout(function() {
          window.location.href = "' . $modifiedUrl . '";
        }, 1); // Delay of 0.001 seconds 
      </script>
    ';
    
    // Stop further execution of PHP code
    exit;
}
if (isset($_GET['edit_event']) && $_GET['edit_event'] === "success") {
  // Get the current URL
  $currentUrl = $_SERVER['REQUEST_URI'];
  
  // Remove the '&edit_event=success' from the URL
  $modifiedUrl = str_replace('&edit_event=success', '', $currentUrl);
  
  // Print the JavaScript code to show the alert and redirect after a delay
  echo '
    <script type="text/javascript">
      alert("Event updated successfully");
      setTimeout(function() {
        window.location.href = "' . $modifiedUrl . '";
      }, 1); // Delay of 0.001 seconds 
    </script>
  ';
  
  // Stop further execution of PHP code
  exit;
}

if (isset($_GET['post_event']) && $_GET['post_event'] === "success") {
    // Get the current URL
    $currentUrl = $_SERVER['REQUEST_URI'];
  
    // Remove the '&post_event=success' from the URL
    $modifiedUrl = str_replace('&post_event=success', '', $currentUrl);
    
    // Print the JavaScript code to show the alert and redirect after a delay
    echo '
      <script type="text/javascript">
        alert("Event posted successfully");
        setTimeout(function() {
          window.location.href = "' . $modifiedUrl . '";
        }, 1); // Delay of 0.001 seconds 
      </script>
    ';
    
    // Stop further execution of PHP code
    exit;
}

if (isset($_GET['reserve']) && $_GET['reserve'] === "success") {
    // Get the current URL
    $currentUrl = $_SERVER['REQUEST_URI'];
  
    // Remove the '&reserve=success' from the URL
    $modifiedUrl = str_replace('&reserve=success', '', $currentUrl);
    
    // Print the JavaScript code to show the alert and redirect after a delay
    echo '
      <script type="text/javascript">
        alert("You have successfully made a reservation for this event.");
        setTimeout(function() {
          window.location.href = "' . $modifiedUrl . '";
        }, 1); // Delay of 0.001 seconds 
      </script>
    ';
    
    // Stop further execution of PHP code
    exit;
}

if (isset($_GET['reserve']) && $_GET['reserve'] === "exist") {
  // Get the current URL
  $currentUrl = $_SERVER['REQUEST_URI'];

  // Remove the '&reserve=exist' from the URL
  $modifiedUrl = str_replace('&reserve=exist', '', $currentUrl);
  

  // Print the JavaScript code to show the alert and redirect after a delay
  echo '
    <script type="text/javascript">
      alert("You have already made a reservation for this event.");
      setTimeout(function() {
        window.location.href = "' . $modifiedUrl . '";
      }, 1); // Delay of 0.001 seconds 
    </script>
  ';
  
  // Stop further execution of PHP code
  exit;
}

if (isset($_GET['reserve']) && $_GET['reserve'] === "fail") {
  // Get the current URL
  $currentUrl = $_SERVER['REQUEST_URI'];

  // Remove the '&reserve=fail' from the URL
  $modifiedUrl = str_replace('&reserve=fail', '', $currentUrl);
  
  // Print the JavaScript code to show the alert and redirect after a delay
  echo '
    <script type="text/javascript">
      alert("Failed to make a reservation. Please try again.");
      setTimeout(function() {
        window.location.href = "' . $modifiedUrl . '";
      }, 1); // Delay of 0.001 seconds 
    </script>
  ';
  
  // Stop further execution of PHP code
  exit;
}

if (isset($_GET['cancel_reserve']) && $_GET['cancel_reserve'] === "success") {
    // Get the current URL
    $currentUrl = $_SERVER['REQUEST_URI'];
  
    // Remove the '&cancel_reserve=success' from the URL
    $modifiedUrl = str_replace('&cancel_reserve=success', '', $currentUrl);
    
    // Print the JavaScript code to show the alert and redirect after a delay
    echo '
      <script type="text/javascript">
        alert("Your reservation for this event has been canceled");
        setTimeout(function() {
          window.location.href = "' . $modifiedUrl . '";
        }, 1); // Delay of 0.001 seconds 
      </script>
    ';
    
    // Stop further execution of PHP code
    exit;
}

if (isset($_GET['cancel_reserve']) && $_GET['cancel_reserve'] === "fail") {
  // Get the current URL
  $currentUrl = $_SERVER['REQUEST_URI'];

  // Remove the '&cancel_reserve=fail' from the URL
  $modifiedUrl = str_replace('&cancel_reserve=fail', '', $currentUrl);
  
  // Print the JavaScript code to show the alert and redirect after a delay
  echo '
    <script type="text/javascript">
      alert("Failed to cancel the reservation. Please try again.");
      setTimeout(function() {
        window.location.href = "' . $modifiedUrl . '";
      }, 1); // Delay of 0.001 seconds 
    </script>
  ';
  
  // Stop further execution of PHP code
  exit;
}

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
    //counts the number of admins in this community 
    $query_count = "SELECT COUNT(*) AS count FROM `joins` WHERE role = 'admin' AND c_ID = $c_IDURL";
    $query_count_run = mysqli_query($connection, $query_count);
    $count_result = mysqli_fetch_assoc($query_count_run);
    $admin_count = $count_result['count'];
    //selects the admins in this community 
    $query_is_admin = "SELECT * FROM `joins` WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = $c_IDURL AND role = 'admin'";
    $query_is_admin_run = mysqli_query($connection, $query_is_admin);
    //check if there is less then one admin in this community 
      if(($admin_count <= 1 )&& (mysqli_num_rows($query_is_admin_run)))
      {
         echo "<script>alert('You Are The Only Admin In This Community, You Could Not Leave.');</script>";    
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
    $currenpate = date('Y-m-d');  // Format: YYYY-MM-DD
    $currentTime = date('H:i:s');  // Format: HH:MM:SS
    $e_ID = $_POST["event_ID"];
    $student_id = $_POST["student_id"];
    //check if the user is already in the community
    $sql = "SELECT * FROM reservation WHERE s_ID = '$student_id' AND e_ID = '$e_ID'";
    $result = mysqli_query($connection, $sql);
    if(mysqli_num_rows($result) > 0)
    {
        // record already exists
        header ("Location: community_page.php?c_ID=".$c_IDURL."&reserve=exist");
      } 

    else 
    {
        // insert the record
        $sql = "INSERT INTO reservation (`r_date`, `r_time`,`s_ID` , `e_ID`) VALUES ('$currenpate','$currentTime','$student_id' , '$e_ID')";
        if(mysqli_query($connection, $sql))
        {
            // reserve successful
            header ("Location: community_page.php?c_ID=".$c_IDURL."&reserve=success");
        } 

        else 
        {
            // reserve failed
            header ("Location: community_page.php?c_ID=".$c_IDURL."&reserve=fail");
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
      //cancel succeed 
      if($query_rundlt_reserve)
      {
        header ("Location: community_page.php?c_ID=".$c_IDURL."&cancel_reserve=success");

      }
      else 
      {
        // cancel failed
        header ("Location: community_page.php?c_ID=".$c_IDURL."&cancel_reserve=fail");

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

    //if the feedback button is pressed, redirect the user to feedback page
    if (isset($_POST["feedback"])) 
    {
      $e_ID = $_POST["event_ID"];
      header ("Location: ../feedback/feedback.php?e_ID=".$e_ID."&c_ID=".$c_IDURL);

    }

    //if the check reservation button is pressed, redirect the user to check reservation page
    if (isset($_POST["check_reservation"])) 
    {
      $e_ID = $_POST["event_ID"];
      header ("Location: ../reservation/check_reservation.php?e_ID=".$e_ID);
    }

    //if the view feedback button is pressed, redirect the user to the view feedback page
    if (isset($_POST["view_feedback"])) 
    {
      $e_ID = $_POST["event_ID"];
      header ("Location: ../feedback/view_feedback.php?e_ID=".$e_ID);
    }
?>

<!-- Community Content -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="row">
      <div class="rightcolumn">
        <div class="card">

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
            echo "<input type='submit' name='leave' id = 'leave' value='Leave' />";
            echo "</form>";
        }
        else
        {
            echo "<form method='POST'>";
            echo "<input type='hidden' name='community_id' value='" . $c_IDURL . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<input type='submit' name='join' id = 'join' value='Join' />";
            echo "</form>";
        }
    }

    else 
    {
        echo '<p>Community not found.</p>';
    }

      ?>    
      <?php
      //check if the user is an admin of the community 
      $querypost = "SELECT * FROM `joins` WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = $c_IDURL AND role = 'admin'";
      $querypostrun = mysqli_query($connection, $querypost);
      //check if the user is a committee of the community
      $querypost2 = "SELECT * FROM `joins` WHERE s_ID = {$_SESSION["s_ID"]} AND c_ID = $c_IDURL AND (role = 'committee')";
      $querypostrun2 = mysqli_query($connection, $querypost2);
      //if the user is an admin
      if(mysqli_num_rows($querypostrun) > 0)
      {
        echo "<form method='POST' action=''>";
        echo "<input type='submit' name='post_event' id = 'post_event' value='Post Event' /> <br>";
        echo "<input type='submit' name='member_list' id = 'member_list' value='Member List' />";
        echo "</form>";
      }
      //select the event information in this community
      $query_view_event= "SELECT `e_ID`,`e_name`, `e_description`, `e_venue`, `e_date_start`, `e_date_end`, `e_time` FROM event_ WHERE c_ID = $c_IDURL ORDER BY `event_`.`e_ID` DESC";
      $query_view_event_run = mysqli_query($connection, $query_view_event);
      $query_role = "";
      //if there is event posted in this community
      if(mysqli_num_rows($query_view_event_run) > 0)
      {
              
      ?>
        </div>
        </div>
        <div class="leftcolumn"><?php
        //print out all the event in this comminity
        while($row = mysqli_fetch_assoc($query_view_event_run))
        {
          // echo '<tr>';
          ?><div class="card"><?php
          echo "<h2><strong>" . $row['e_name']. "</strong></h2>" ;
          echo "<p>" . $row['e_description'] . "</p>";
          echo "<p><strong> Venue: </strong>" . $row['e_venue'] . "</p>";
          echo "<p><strong> Start Date: </strong>" . $row['e_date_start'] . "</p>";
          echo "<p><strong> End Date: </strong>" . $row['e_date_end'] . "</p>";
          echo "<p><strong> Time: </strong>" . $row['e_time'] . "</p>";
          
          //check if the user has reserved the event
          $query_reserve = "SELECT * FROM `reservation` WHERE s_ID = '{$_SESSION["s_ID"]}' AND e_ID = '{$row["e_ID"]}'";
          $query_reserve_run = mysqli_query($connection, $query_reserve);

          //if the user has reserved the event
          if(mysqli_num_rows($query_reserve_run)>0)
          {
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<input type='submit' name='cancel_reserve' id = 'cancel_reserve' value='Cancel reservation' />";
            echo "</form>";
          }
          
          else
          {
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<input type='hidden' name='student_id' value='" . $_SESSION["s_ID"] . "' />";
            echo "<input type='submit' name='reserve' id = 'reserve' value='Reserve Event' />";
            echo "</form>";
          }

          //if the event has ended
          if(date('Y-m-d') > $row['e_date_end'])
          {
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<input type='submit' name='feedback' id = 'feedback' value='Feedback' /> ";
            echo "</form>";
            echo '<br>';
          }

          //if the event has not ended 
          else if(date('Y-m-d') <= $row['e_date_end'])
          {
            echo "<form method='POST' action=''>";
            echo "<input type='submit' name='#' id = 'feedback_not_available' value='Feedback not yet available' disabled style='background-color: lightgray;' />";
            echo "</form>";
            echo '<br>';
          }

          //if the user is a commitee in this community
          if(mysqli_num_rows($querypostrun2) > 0)
          {
            ?><div class="committee_actions"><?php
            echo '<br>';
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<input type='submit' name='edit_event' id='edit_event' value='Edit' /> ";
            echo "<input type='submit' name='check_reservation' id = 'check_reservation' value='Check Reservation' /> ";
            echo "<input type='submit' name='view_feedback' id = 'view_feedback' value='View Feedback' /> ";
            echo "</form>";
            echo '<br>';
            echo '</div>';
          }
          
          //if the user is an admin then show cancel event button
          if(mysqli_num_rows($querypostrun) > 0)
          { 
            ?><div class="committee_actions"><?php
            echo '<br>';
            echo "<form method='POST' action=''>";
            echo "<input type='hidden' name='event_ID' value='" . $row["e_ID"] . "' />";
            echo "<input type='submit' name='edit_event' id='edit_event' value='Edit' /> ";
            echo "<input type='submit' name='check_reservation' id = 'check_reservation' value='Check Reservation' /> ";
            echo "<input type='submit' name='view_feedback' id = 'view_feedback' value='View Feedback' /> ";
            echo "<input type='submit' name='cancel_event' id='cancel_event' value='Cancel event' /> ";
            echo "</form>";
            echo '<br>';
            echo '</div>';
          }
          
          echo '</div>';
          
        }
      }
      
      
      ?>
      </div>
  </div>
</div>

<footer>
</footer>

</body>
</html>