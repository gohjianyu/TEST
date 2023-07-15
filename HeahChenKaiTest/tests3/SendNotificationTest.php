<?php

use PHPUnit\Framework\TestCase;
// Include the code to be tested
require "D:/college D/Sem 7/project/code/post_event.php";

class SendNotificationTest extends TestCase
{
    protected $connection;
    protected $testDatabase = 'event_management';

    protected function setUp(): void
    {
        // Establish a connection to the test database
        $this->connection = new mysqli('localhost', 'root', '', $this->testDatabase);

        // Check if the connection was successful
        if ($this->connection->connect_error) {
            die('Connection failed: ' . $this->connection->connect_error);
        }

        // Create the necessary tables in the test database
        $this->createTables();
    }

    protected function tearDown(): void
    {
        // Drop the tables from the test database
        $this->dropTables();

        // Close the database connection
        $this->connection->close();
    }

    protected function createTables(): void
    {
        // Create the necessary tables in the test database
        $query = "CREATE TABLE IF NOT EXISTS event_ (
            e_id INT AUTO_INCREMENT PRIMARY KEY,
            e_name VARCHAR(255) NOT NULL,
            e_date_start DATE NOT NULL,
            e_date_end DATE NOT NULL,
            e_description TEXT,
            e_time TIME NOT NULL,
            e_venue VARCHAR(255) NOT NULL,
            c_id INT NOT NULL
        )";

        $this->connection->query($query);

        $query = "CREATE TABLE IF NOT EXISTS notification (
            n_id INT AUTO_INCREMENT PRIMARY KEY,
            n_name VARCHAR(255) NOT NULL,
            n_content TEXT NOT NULL,
            n_date DATETIME NOT NULL,
            e_id INT,
            c_id INT,
            s_id INT,
            FOREIGN KEY (e_id) REFERENCES event_(e_id),
            FOREIGN KEY (c_id) REFERENCES community(c_id),
            FOREIGN KEY (s_id) REFERENCES staff(s_id)
        )";

        $this->connection->query($query);
    }

    protected function dropTables(): void
    {
        // Drop the tables from the test database
        $query = "DROP TABLE IF EXISTS event_, notification";
        $this->connection->query($query);
    }

    public function testInsertNotification()
    {
        // Set up the required $_POST data
        $_POST['upload'] = true;
        $_POST['e_name'] = 'reeeeeeeeeee';
        $_POST['e_start_date'] = '2023-06-20';
        $_POST['e_end_date'] = '2023-06-20';
        $_POST['e_description'] = 'weyyyyyyyyyyy';
        $_POST['e_time'] = '02:30';
        $_POST['e_venue'] = 'ddddddddddd';
        $_POST['n_name'] = 'ddddddddddd has posted an event';

        $_GET['c_ID'] = 7; // Set the community ID

        

        // Check if the event was inserted successfully
        $query = "SELECT * FROM event_";
        $eventResult = $this->connection->query($query);
        $this->assertEquals(3, $eventResult->num_rows, "Failed to insert event");

        // Check if the notification was inserted successfully
        $query = "SELECT * FROM notification";
        $notificationResult = $this->connection->query($query);
        $this->assertEquals(6, $notificationResult->num_rows, "Failed to insert notification");

        // Retrieve the inserted event ID
        $eventRow = $eventResult->fetch_assoc();
        $insertedEventId = $eventRow['e_ID'];

        // Retrieve the inserted notification data
        $notificationRow = $notificationResult->fetch_assoc();

        // Assert the expected event and notification data
        $this->assertEquals($_POST['n_name'], $notificationRow['n_name']);
        $this->assertEquals('reeeeeeeeeee has been posted', $notificationRow['n_content']);
        $this->assertEquals($_POST['e_name'], $eventRow['e_name']);
        $this->assertEquals($_POST['e_start_date'], $eventRow['e_date_start']);
        $this->assertEquals($_POST['e_end_date'], $eventRow['e_date_end']);
        $this->assertEquals($_POST['e_description'], $eventRow['e_description']);
        $this->assertEquals($_POST['e_time'], $eventRow['e_time']);
        $this->assertEquals($_POST['e_venue'], $eventRow['e_venue']);
        $this->assertEquals($_GET['c_ID'], $eventRow['c_ID']);
        $this->assertEquals($insertedEventId, $notificationRow['e_ID']);
    }
}
