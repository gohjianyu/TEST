<?php

use PHPUnit\Framework\TestCase;

require_once 'community_page.php';

class reservationTest extends TestCase
{
    private $connection; // Database connection object

    protected function setUp(): void
    {
        // Set up the database connection
        $this->connection = new mysqli('localhost', 'root', '', 'event_management');
    }

    public function testReserveEventSuccess()
    {
        // Simulate the reservation process
        $eventID = 1;
        $studentID = 1;

        // Check if the event is available for reservation
        $query = "SELECT * FROM event_ WHERE e_ID = $eventID";
        $result = mysqli_query($this->connection, $query);

        if (mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $eventStartDate = $row['e_date_start'];
            $currentDate = date('Y-m-d');

            // Check if the event has not started yet
            if ($currentDate < $eventStartDate) {
                // Make the reservation
                $query = "INSERT INTO reservation (r_date, r_time, s_ID, e_ID) VALUES (CURDATE(), CURTIME(), $studentID, $eventID)";
                $reservationResult = mysqli_query($this->connection, $query);

                if ($reservationResult) {
                    // Reservation successful
                    $this->assertTrue(true);
                } else {
                    // Reservation failed
                    $this->assertTrue(false, 'Failed to make a reservation.');
                }
            } else {
                // Event has already started, cannot make a reservation
                $this->assertTrue(false, 'Reservation unavailable. Event has already started.');
            }
        } else {
            // Event not found
            $this->assertTrue(false, 'Event not found.');
        }
    }
}