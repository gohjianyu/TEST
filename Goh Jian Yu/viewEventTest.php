<?php
use PHPUnit\Framework\TestCase;

class viewEventTest extends TestCase
{
    protected $connection;

    protected function setUp(): void
    {
        $host = 'localhost';
        $username = 'root';
        $password = '';
        $database = 'event_management';

        // Create a new database connection
        $this->connection = new mysqli($host, $username, $password, $database);

        // Check if the connection was successful
        if ($this->connection->connect_error) {
            die('Database connection failed: ' . $this->connection->connect_error);
        }
    }

    protected function tearDown(): void
    {
        // Close the database connection
        $this->connection->close();
    }

    /**
     * @covers ::testEventRetrieval
     */
    public function testEventRetrieval()
    {
        // Execute the event retrieval query
        $query = 'SELECT `e_ID`, `e_name`, `e_description`, `e_venue`, `e_date_start`, `e_date_end`, `e_time` FROM event_';
        $result = $this->connection->query($query);

        // Assert that the query was successful
        $this->assertTrue($result !== false);

        // Assert that at least one event is retrieved
        $this->assertGreaterThan(0, $result->num_rows);

        // Process the retrieved event data
        $events = [];
        while ($row = $result->fetch_assoc()) {
            $events[] = $row;
        }

        // Assert specific event data
        $this->assertNotEmpty($events, 'No events found in the database.');

        // Compare retrieved event data with website data
        $websiteEvents = $this->getWebsiteEvents(); 

        // Assert that the number of events is the same
        $this->assertCount(count($websiteEvents), $events);

        // Compare individual event data
        foreach ($websiteEvents as $index => $websiteEvent) {
            $this->assertEquals($websiteEvent['e_name'], $events[$index]['e_name']);
            $this->assertEquals($websiteEvent['e_description'], $events[$index]['e_description']);
            $this->assertEquals($websiteEvent['e_venue'], $events[$index]['e_venue']);
            $this->assertEquals($websiteEvent['e_date_start'], $events[$index]['e_date_start']);
            $this->assertEquals($websiteEvent['e_date_end'], $events[$index]['e_date_end']);
            $this->assertEquals($websiteEvent['e_time'], $events[$index]['e_time']);
        }
    }

    /**
    * @return array
    */
    private function getWebsiteEvents(): array
    {
        $websiteEvents = [];

        //query to fetch events from website's database
        $query = 'SELECT `e_name`, `e_description`, `e_venue`, `e_date_start`, `e_date_end`, `e_time` FROM event_';
        $result = $this->connection->query($query);

        //store the event data into $websiteEvents if the database has events
        if ($result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $websiteEvents[] = $row;
            }
        }

        return $websiteEvents;
    }
}

