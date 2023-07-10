<?PHP
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header ("Location: ../login/login2.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Post Event</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../homepage/homepage2.css">
<link rel="stylesheet" href="event.css">
<link rel="stylesheet" href="../feedback/form.css">
<link rel="stylesheet" href="../bg/bg.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>


</head>
<body>

<?php
require "../navbar.php";
?>
</div>
</div>
<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
<img src="../homepage/distedlogo.png" alt="Disted Logo" style= "height:20%; width:50%;"> 
</header>

<?php
//connect to database 
$c_IDURL = $_GET['c_ID'];
$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db = mysqli_select_db($connection,'event_management');

//if the form is uploaded
if(isset($_POST['upload']))
{
    $e_name = $_POST['e_name'];
    $e_start_date = $_POST['e_start_date'];
    $e_end_date = $_POST['e_end_date'];
    $e_description = $_POST['e_description'];
    $e_time = $_POST['e_time'];
    $e_venue = $_POST['e_venue'];
    // Pattern to match the 'YYYY-MM-DD' format
    $pattern = '/^\d{4}-\d{2}-\d{2}$/';

    //if the connection is established 
    if($db)
    {
        //check if the event of the same name is in the database
        $query = "SELECT * FROM event_ WHERE e_name = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $e_name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        $errors = array();

        //check if event name is already taken
        if (mysqli_num_rows($result) > 0)
        {
          $errors[] = "Event Name already taken";
        }
        //check if fields are empty
        if (empty($e_name) || empty($e_start_date) || empty($e_end_date) || empty($e_time) || empty($e_venue) || empty($e_description)) {
          $errors[] = "All fields are required.";
        }

        //validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $e_start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $e_end_date)) {
          $errors[] = "Invalid date format. Please use YYYY-MM-DD.";
        }

        //validate time format
        if (!preg_match('/^\d{2}:\d{2}$/', $e_time)) {
          $errors[] = "Invalid time format. Please use HH:MM.";
        }

        //validate date range
        if ($e_start_date > $e_end_date) {
          $errors[] = "End date must come after start date.";
        }

        // Validate for backdated event
        if ($e_start_date < date('Y-m-d')) {
          $errors[] = "Cannot post a backdated event.";
        }

        //if there are any errors, display them
        if (!empty($errors)) 
        {
          //adds all the error messages to single variable
          $errorMessages = implode('\n', $errors);
          echo '<script type="text/javascript">alert("' . $errorMessages . '");</script>';
          $_SESSION['post_event_error_message'] = $errorMessages;
        }

        else
        {
            $query_comm = "SELECT * FROM community WHERE c_ID = $c_IDURL";
            $query_run_comm = mysqli_query($connection,$query_comm);
            
            if ($query_run_comm) {
              $community_data = mysqli_fetch_assoc($query_run_comm);
              $c_name = $community_data['c_name'];

            //insert the event into the event table
            $query = "INSERT INTO event_ (e_name, e_date_start, e_date_end, e_description, e_time, e_venue, c_id) VALUES ('$e_name', '$e_start_date',  '$e_end_date', '$e_description', '$e_time', '$e_venue', $c_IDURL)";
            $query_run = mysqli_query($connection,$query);
            //get the last auto-incremented id in the database
            $e_id = mysqli_insert_id($connection);
            $query_notification = "INSERT INTO `notification` (n_name, n_content, n_date, e_ID, c_ID, s_ID) VALUES (CONCAT('$c_name', ' has posted an event'), CONCAT('$e_name', ' has been posted'), NOW(), $e_id, $c_IDURL,NULL)";
            $query_run_notification = mysqli_query($connection,$query_notification);
            }
            if($query_run)
            {
              header("Location: ../community/community_page.php?c_ID=" . $c_IDURL . "&post_event=success");

            }
          //}
          
          else
          {
            //prints error message
            echo '<script type="text/javascript"> alert("Failed to post event")</script>';
          }

        }

    }

}
?>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <center>    
        <h1>Post Event</h1>
        <form action="" method="POST" enctype="multipart/form-data">
            
            <label>Event name: </label><br>
            <input type="text" name="e_name" placeholder="Enter Event Name"/><br> 
            <label>Event start date: </label><br>
            <input type="date" name="e_start_date" placeholder="Enter Event start date (yyyy-mm-dd)"/><br>
            <label>Event end date: </label><br>
            <input type="date" name="e_end_date" placeholder="Enter Event end date (yyyy-mm-dd)"/><br>
            <label>Event description: </label><br>
            <textarea name="e_description" placeholder="Enter Event Description" style="max-width: 500px; max-height: 300px; width: 500px; height: 300px; min-width: 500px; min-height: 150px;"></textarea><br> 
            <label>Event time: </label><br>
            <input type="time" name="e_time" placeholder="Enter Event time"/><br>
            <label>Event venue: </label><br>
            <input type="text" name="e_venue" placeholder="Enter Event venue"/><br>
            <label>Community icon: (Max 64KB)</label><br>            
            <input type="submit" name="upload" value="Post Event"/><br>
        </form>
    </center>
  </div>
</div>


</body>
</html>


	</body>
	</html>
