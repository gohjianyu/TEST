<?php

use PHPUnit\Framework\TestCase;

class memberlistTest extends TestCase
{
    protected $connection;

    protected function setUp(): void
    {
        // Simulating database connection
        $this->connection = mysqli_connect("localhost", "root", "", "event_management");
    }

    protected function tearDown(): void
    {
        // Clean up resources after each test
        mysqli_close($this->connection);
    }

    public function testRoleUpdateSuccess()
    {
        // Simulate form data
        $_GET['c_ID'] = 7;
        $_POST['student_id'] = 1;
        $_POST['updated_role'] = 'member';

        // Execute the code block
        ob_start(); // Start output buffering to capture the echoed message
        require_once 'D:\unitTest\tests3\member_list_test.php'; // Include the PHP file to be tested
        ob_end_clean(); // Discard the echoed message

        // Check if the role update was successful
        $query = "SELECT role FROM joins WHERE s_ID = 1 AND c_ID = 7";
        $result = mysqli_query($this->connection, $query);
        $row = mysqli_fetch_assoc($result);
        $updatedRole = $row['role'];

        $this->assertEquals('member', $updatedRole);
    }

    public function testRoleUpdateError()
    {
        // Simulate form data
        $_GET['c_ID'] = 7;
        $_POST['student_id'] = 1;
        $_POST['updated_role'] = 'invalid_role';

        // Execute the code block
        ob_start(); // Start output buffering to capture the echoed message
        require_once 'D:\unitTest\tests3\member_list_test.php'; // Include the PHP file to be tested
        ob_end_clean(); // Discard the echoed message

        // Check if an error message was displayed
        $expectedMessage = "<script>alert('Error updating role.');</script>";
        $this->expectOutputString($expectedMessage);
    }
}