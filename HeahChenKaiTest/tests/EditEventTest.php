<?php
use PHPUnit\Framework\TestCase;

// Include the PHP file to be tested
require "D:/college D/Sem 7/project/code/edit_event.php";

class EditEventTest extends TestCase
{
    /**
    * @covers ::testEventUpdate
    */
    public function testEventUpdate()
    {
        // Simulate logged-in user session
        $_SESSION['login'] = true;

        // Simulate GET request with event ID parameter
        $_GET['e_ID'] = 66;

        // Simulate form data for event update
        $_POST['event_id'] = 66;
        $_POST['e_name'] = 'Updated Event Name2';
        $_POST['e_start_date'] = '2023-06-18';
        $_POST['e_end_date'] = '2023-06-19';
        $_POST['e_description'] = 'Updated Event Description2';
        $_POST['e_time'] = '11:00';
        $_POST['e_venue'] = 'Updated Event Venue2';

        // Start output buffering to capture any echo or header statements
        ob_start();

        

        // Get the captured output
        $output = ob_get_clean();

        // Display the captured output for debugging
        echo $output;

        // Assert that the event update was successful
        $this->assertStringContainsString('', $output);

        // Assert that the event data was updated in the database
        $updatedEventData = $this->getEventDataFromDatabase(66);
        $this->assertEquals('Updated Event Name', $updatedEventData['e_name']);
        $this->assertEquals('2023-06-17', $updatedEventData['e_date_start']);
        $this->assertEquals('2023-06-18', $updatedEventData['e_date_end']);
        $this->assertEquals('Updated Event Description', $updatedEventData['e_description']);
        $this->assertEquals('12:00', $updatedEventData['e_time']);
        $this->assertEquals('Updated Event Venue', $updatedEventData['e_venue']);
    }

    private function getEventDataFromDatabase($eventID)
    {
        // Connect to the database
        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
        $db = mysqli_select_db($connection, 'event_management');

        if (!$connection || !$db) {
            $this->fail('Failed to connect to the database');
        }

        // Retrieve the event data from the database
        $query = "SELECT * FROM event_ WHERE e_ID = $eventID";
        $result = mysqli_query($connection, $query);
        
        if (!$result) {
            $this->fail("Failed to retrieve event data for ID: $eventID");
        }

        $eventData = mysqli_fetch_assoc($result);

        // Close the database connection
        mysqli_close($connection);

        return $eventData;
    }
}
