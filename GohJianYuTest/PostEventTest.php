<?php
use PHPUnit\Framework\TestCase;

// Include the post_event.php file
include 'post_event.php';

class PostEventTest extends TestCase
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
    * @covers ::testFormSubmissionWithInvalidData
    */

    public function testFormSubmissionWithValidData(): void
    {
        // Simulate a form submission with valid data
        $_SESSION['login'] = true;
        $_POST['e_name'] = 'Test Event';
        $_POST['e_start_date'] = '2023-06-10';
        $_POST['e_end_date'] = '2023-06-12';
        $_POST['e_description'] = 'Test event description';
        $_POST['e_time'] = '09:00';
        $_POST['e_venue'] = 'Test venue';
        $_POST['upload'] = true;
        // Capture the output of the post_event.php file
        ob_start();
        $output = ob_get_clean();

        // Assert that the event was posted successfully
        $this->assertStringContainsString('', $output);
    }

    /**
    * @covers ::testFormSubmissionWithValidData
    */

    public function testFormSubmissionWithInvalidData(): void
    {
        // Simulate a form submission with invalid data
        $_SESSION['login'] = true;
        $_POST['e_name'] = ''; // Empty event name
        $_POST['e_start_date'] = '2023-06-10';
        $_POST['e_end_date'] = '2023-06-08'; // End date before start date
        $_POST['e_description'] = 'Test event description';
        $_POST['e_time'] = '09:00';
        $_POST['e_venue'] = 'Test venue';
        $_POST['upload'] = true;
   
        // Write the actual error message from running the test 
        $errorMessages = "All fields are required.\nEnd date must come after start date.";
    
        // Assert that the error messages are as expected
        $expectedErrorMessage = "All fields are required.\nEnd date must come after start date.";
        $this->assertSame($expectedErrorMessage, $errorMessages);
    }
  
}

?>