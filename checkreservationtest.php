<?php

use PHPUnit\Framework\TestCase;

require_once 'check_reservation.php';

class checkreservationtest extends TestCase
{
    private $connection; // Database connection object

    protected function setUp(): void
    {
        // Set up the database connection
        $this->connection = new mysqli('localhost', 'root', '', 'event_management');
    }

    public function testCheckReservation()
    {
        // Simulate checking reservations for an event
        $eventID = 84;

        // Retrieve the reservations for the event from the database
        $query = "SELECT * FROM reservation WHERE e_ID = $eventID";
        $result = mysqli_query($this->connection, $query);

        // Check if there are reservations for the event
        $this->assertGreaterThan(0, mysqli_num_rows($result));

        }
    }

