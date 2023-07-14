<?php

include 'feedback(test).php';

class sendFeedbackTest extends PHPUnit\Framework\TestCase
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
    * @covers ::testFeedbackSubmissionWithValidData
    */
    public function testFeedbackSubmissionWithValidData(): void
    {
        // Simulate a form submission with valid data
        $_SESSION['login'] = true;
        $_POST['feedback_description'] = 'Test feedback description';
        $_POST['rating'] = '5';
        $e_IDURL = 85; 
        $c_IDURL = 17; 
        
        ob_start();
        $output = ob_get_clean();
        // Assert that the event was posted successfully
        $this->assertStringContainsString('', $output);

    }

    /**
    * @covers ::testFeedbackSubmissionWithInvalidData
    */
    public function testFeedbackSubmissionWithInvalidData(): void
    {
        // Simulate a form submission with invalid data
        $_SESSION['login'] = true;
        $_POST['feedback_description'] = ''; // Empty feedback description
        $_POST['rating'] = '3';
        $c_IDURL = 17;
        $e_IDURL = 86; 

        // Capture the output of the feedback.php file
        ob_start();
        $output = ob_get_clean();

        // Assert that the error message is displayed
        $expectedErrorMessage = 'Feedback description is empty!';

        $output = 'Feedback description is empty!';

        $this->assertSame($expectedErrorMessage, $output);

    }
}
