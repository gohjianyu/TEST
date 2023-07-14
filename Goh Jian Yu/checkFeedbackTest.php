<?php
use PHPUnit\Framework\TestCase;

class checkFeedbackTest extends TestCase
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
    * @covers ::testFeedbackViewWithFeedbacks
    */
    public function testFeedbackViewWithFeedbacks(): void
    {
        // Simulate having feedback data in the database
        // Execute the event retrieval query
        $query = 'SELECT * FROM `feedback` WHERE e_ID = 84';
        $result = $this->connection->query($query);

        // Assert that the query was successful
        $this->assertTrue($result !== false);

        // Assert that at least one event is retrieved
        $this->assertGreaterThan(0, $result->num_rows);

        // Process the retrieved event data
        $feedback = [];
        while ($row = $result->fetch_assoc()) {
            $feedback[] = $row;
        }

        // Assert specific event data
        $this->assertNotEmpty($feedback, 'There are no Feedbacks');

        // Compare retrieved event data with website data
        $websiteFeedbacks = $this->getWebsiteFeedbacks(); 
        
        // Assert that the number of events is the same
        $this->assertCount(count($websiteFeedbacks), $feedback);

        // Compare individual event data
        foreach ($websiteFeedbacks as $index => $websiteFeedback) {
            $this->assertEquals($websiteFeedback['f_rating'], $feedback[$index]['f_rating']);
            $this->assertEquals($websiteFeedback['f_content'], $feedback[$index]['f_content']);
        }
    }

    /**
    * @return array
    */
    private function getWebsiteFeedbacks(): array
    {
        $websiteFeedbacks = [];

        //query to fetch events from website's database
        $query = 'SELECT * FROM `feedback` WHERE e_ID = 84;';
        $result = $this->connection->query($query);

        //store the event data into $websiteFeedbacks if the database has feedbacks
        if ($result !== false && $result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $websiteFeedbacks[] = $row;
            }
        }

        return $websiteFeedbacks;
    }
}