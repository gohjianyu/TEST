<?php

use PHPUnit\Framework\TestCase;


class DltEventTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up database connection or any other necessary initialization
    }
    /**
    * @covers ::testEventUpdate
    */
    public function testCancelEvent()
    {
        // Mock the $_GET superglobal variables
        
        $_GET['e_ID'] = 38;
        $_GET['c_ID'] = 10;

        // Execute the cancel event script
        require "D:/xampp/htdocs/project/code/cancel_event.php";

        // Query the database to check if the event was deleted
        $eventExists = $this->checkEventExists(38); // Assuming 7 is the event ID

        // Assert that the event was deleted
        $this->assertFalse($eventExists);
    }

    private function checkEventExists($eventID)
    {
        // Query the database to check if the event exists
        // Adjust the database connection and query based on your environment
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'event_management';
        $connection = new mysqli($host, $username, $password, $database);
        $query = "SELECT * FROM `event_` WHERE e_ID = $eventID";
        $result = $connection->query($query);

        // Return true if the event exists, false otherwise
        return $result->num_rows > 0;
    }
}
//.\vendor\bin\phpunit --testsuite "Test Suite 2"