<?php
$e_ID = $_GET['e_ID'];
$c_IDURL = $_GET['c_ID'];

require '../configure.php';
// Connect to the database
$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db = mysqli_select_db($connection, 'event_management');

// Fetch event details
$query_event = "SELECT * FROM event_ WHERE e_ID = $e_ID";
$result_event = mysqli_query($connection, $query_event);
$row_event = mysqli_fetch_assoc($result_event);

$e_name = $row_event['e_name']; // Get the event name

// Fetch reservations for the event
$query_reservations = "SELECT * FROM reservation WHERE e_ID = $e_ID";
$result_reservations = mysqli_query($connection, $query_reservations);

while ($row = mysqli_fetch_assoc($result_reservations)) {
  $s_ID = $row['s_ID'];

  // Insert a notification for each reservation
  $query_notification = "INSERT INTO `notification` (n_name, n_content, n_date, e_ID, c_ID, s_ID) VALUES ('Cancellation of event', CONCAT('$e_name', ' has been canceled'), NOW(), $e_ID, $c_IDURL, '$s_ID')";
  $query_run_notification = mysqli_query($connection, $query_notification);
}

// Delete the selected event
$query_dlt_event = "DELETE FROM `event_` WHERE e_ID = $e_ID";
$query_run_dlt_event = mysqli_query($connection, $query_dlt_event);

// Once deleted, go back to the community page
if ($query_run_dlt_event) {
    header("Location: ../community/community_page.php?c_ID=".$c_IDURL);
}
?>
