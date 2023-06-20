<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header("Location: ../login/login2.php");
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
  <title>Edit Event</title>
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

    input {
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

<?php
require '../navbar.php';
$c_IDURL = $_GET['c_ID'];

?>

  <!-- Header -->
  <header class="w3-container w3-black w3-center" style="padding:128px 16px">
    <img src="../homepage/distedlogo.png" alt="Disted Logo" style="height:20%; width:50%;">
  </header>

  <!-- First Grid -->
  <div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
      <center>
        <h1>Edit Event</h1>
        <?php
        // Connect to the database
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        $db = mysqli_select_db($connection, 'event_management');

        if (isset($_GET['e_ID'])) {
          $event_id = $_GET['e_ID'];
          $query = "SELECT * FROM event_ WHERE e_ID = $event_id";
          $result = mysqli_query($connection, $query);
          $row = mysqli_fetch_assoc($result);
        ?>

          <form action="" method="POST">
            <label>Event name:</label><br>
            <input type="text" name="e_name" placeholder="Enter Event Name" value="<?php echo $row['e_name']; ?>" /><br>
            <label>Event start date:</label><br>
            <input type="date" name="e_start_date" placeholder="Enter Event start date (yyyy-mm-dd)" value="<?php echo $row['e_date_start']; ?>" /><br>
            <label>Event end date:</label><br>
            <input type="date" name="e_end_date" placeholder="Enter Event end date (yyyy-mm-dd)" value="<?php echo $row['e_date_end']; ?>" /><br>
            <label>Event description:</label><br>
            <input type="text" name="e_description" placeholder="Enter Event Description" value="<?php echo $row['e_description']; ?>" /><br>
            <label>Event time:</label><br>
            <input type="time" name="e_time" placeholder="Enter Event time" value="<?php echo $row['e_time']; ?>" /><br>
            <label>Event venue:</label><br>
            <input type="text" name="e_venue" placeholder="Enter Event venue" value="<?php echo $row['e_venue']; ?>" /><br>
            <input type="hidden" name="event_id" value="<?php echo $event_id; ?>" />
            <input type="submit" name="update" value="Update Data" /><br>
          </form>

        <?php
        } else {
          echo "Event ID not provided.";
        }
        ?>

      </center>
    </div>
  </div>

  <?php
  // If the form is submitted
  if (isset($_POST['update'])) {
    $event_id = $_POST['event_id'];
    $e_name = $_POST['e_name'];
    $e_start_date = $_POST['e_start_date'];
    $e_end_date = $_POST['e_end_date'];
    $e_description = $_POST['e_description'];
    $e_time = $_POST['e_time'];
    $e_venue = $_POST['e_venue'];
    
    // Pattern to match the 'YYYY-MM-DD' format
    $pattern = '/^\d{4}-\d{2}-\d{2}$/';

    // Connect to the database
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
    $db = mysqli_select_db($connection, 'event_management');

    //check for the event name of different event id
    $query = "SELECT * FROM event_ WHERE e_name = ? AND e_ID != ?";
    $stmt = mysqli_prepare($connection, $query);
    mysqli_stmt_bind_param($stmt, "si", $e_name, $event_id);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);

    $errors = array();

    //check if event name is already taken in other event ids 
    if (mysqli_num_rows($result) > 0)
    {
      $errors[] = "Event Name already taken";
    }
    //check if fields are empty
    if (empty($e_name) || empty($e_start_date) || empty($e_end_date) || empty($e_time) || empty($e_venue) || empty($e_description)) {
      $errors[] = "All fields are required.";
    }

    //validate date format
    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $e_start_date) || !preg_match('/^\d{4}-\d{2}-\d{2}$/', $e_end_date))
    {
      $errors[] = "Invalid date format. Please use YYYY-MM-DD.";
    }

    //validate time format
    if (!preg_match('/^\d{2}:\d{2}$/', $e_time)) 
    {
      $errors[] = "Invalid time format. Please use HH:MM.";
    }

    //validate date range
    if ($e_start_date > $e_end_date) 
    {
      $errors[] = "End date must come after start date.";
    }

    //if there are any errors, display them
    if (!empty($errors)) 
    {
    //adds all the error messages to single variable
      $errorMessages = implode('\n', $errors);
      echo '<script type="text/javascript">alert("' . $errorMessages . '");</script>';
    }

    else
    {
      // Update the event information
      $query = "UPDATE event_ SET e_name='$e_name', e_date_start='$e_start_date', e_date_end='$e_end_date', e_description='$e_description', e_time='$e_time', e_venue='$e_venue' WHERE e_id=$event_id";
      $query_run = mysqli_query($connection, $query);
  
      if ($query_run) {
        echo '<script type="text/javascript"> alert("Event updated successfully")</script>';
        header("Location: ../community/community_page.php?c_ID=" . $c_IDURL);
  
      } else {
        echo '<script type="text/javascript"> alert("Failed to update event")</script>';
      }

    }
  }

  // require '../footer/footer2.html';
  ?>

</body>

</html>