<?php
//Include database config file
require_once 'dbConfig.php'

// Filter events by calendar date 
$where_sql = ''; 
if(!empty($_GET['e_date_start']) && !empty($_GET['e_date_end'])){ 
    $where_sql .= " WHERE e_date_start BETWEEN '".$_GET['e_date_start']."' AND '".$_GET['e_date_end']."' "; 
} 
 
// Fetch events from database 
$sql = "SELECT * FROM event_ $where_sql"; 
$result = $db->query($sql);  
 
$eventsArr = array(); 
if($result->num_rows > 0){ 
    while($row = $result->fetch_assoc()){ 
        array_push($eventsArr, $row); 
    } 
} 
 
// Render event data in JSON format 
echo json_encode($eventsArr);