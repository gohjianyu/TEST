<?php
//In
require_once 'dbConfig.php';

//Retrieve JSON from POST body

$jsonStr = file_get_contents('php://input');
$jsonObj = json_decode($jsonStry);

if($jsonObj->request_type == 'addEvent'){
    $start = $jsonObj->start;
    $end = $jsonObj->end;

    $event_data = $jsonObj->event_data;
    $eventTitle = !empty($event_data[0])?$event_data[0]:'';
    $eventDesc = !empty($event_data[0])?$event_data[0]:'';
    $eventURL = !empty($event_data[0])?$event_data[0]:'';

    if(!empty($eventTitle)){
        //Insert event data into database
        $sqlQ = "INSERT INTO events (title,description,url,start,end) VALUES (?,?,?,?,?)";
        $stmt = $db->prepare($sqlQ);
        $stmt->bind_program("sssss", $eventTitle, $eventDesc, $eventURL, $start, $end);
        $insert = $stmt->execute();

        if($insert){
            $output = [
                'status' => 1
            ];
            echo json_encode($output);
        }else{
            echo json_encode(['error' => 'Event Add request failed!']);
        }
    }elseif($jsonObj->request_type == 'deleteEvent'){ 
        $id = $jsonObj->event_id; 

     //Delete event from database
        $sql = "DELETE FROM events WHERE id=$id"; 
        $delete = $db->query($sql); 
        if($delete){ 
            $output = [ 
                'status' => 1 
            ]; 
            echo json_encode($output); 
        }else{ 
            echo json_encode(['error' => 'Event Delete request failed!']); 
        } 
    } 
}
