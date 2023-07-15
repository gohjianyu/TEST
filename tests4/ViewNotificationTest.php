<?php

use PHPUnit\Framework\TestCase;


class ViewNotificationTest extends TestCase
{
    protected function setUp(): void
    {
        // Set up database connection or any other necessary initialization
    }
    /**
    * @covers ::testEventUpdate
    */
    public function testNotification()
    {
        $eventExists = $this->checkNotificationExists(102);
        // Assert that the event was deleted
        $this->assertTrue($eventExists);
    }

    private function checkNotificationExists($notificationID)
    {
        // Query the database to check if the event exists
        // Adjust the database connection and query based on your environment
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'event_management';
        $connection = new mysqli($host, $username, $password, $database);
        $query = "SELECT * FROM `notification` WHERE n_ID = $notificationID";
        $result = $connection->query($query);

        // Return true if the event exists, false otherwise
        return $result->num_rows > 0;
    }
}