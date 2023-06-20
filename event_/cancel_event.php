<?php
$e_ID = $_GET['e_ID'];
$c_IDURL = $_GET['c_ID'];

require '../configure.php';
// Connect to the database
$connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
$db = mysqli_select_db($connection, 'event_management');
//delete the selected event
$querydlt_event = "DELETE FROM `event_` WHERE e_ID = $e_ID";
$query_rundlt_event = mysqli_query($connection, $querydlt_event);
//once deleted, go back to the community page
if($query_rundlt_event)
{
    header ("Location: ../community/community_page.php?c_ID=".$c_IDURL);
}
?>