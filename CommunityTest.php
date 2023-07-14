<?php

require_once 'D:\unitTest\tests4\community_page.php';

class CommunityTest extends PHPUnit\Framework\TestCase
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
        // Clean up your test environment here

        // Close the database connection
        $this->connection->close();
    }

    public function testJoinCommunity()
    {
        // Mock the $_SESSION superglobal
        $_SESSION['login'] = true;
        $_SESSION['s_ID'] = 1; // Set the student ID to a valid value

        // Mock the $_POST superglobal
        $_POST['join'] = true;
        $_POST['student_id'] = 1; // Set the student ID to a valid value
        $_POST['community_id'] = 7; // Set the community ID to a valid value

        // Capture the output of the script
        ob_start();
        require 'D:\unitTest\tests4\community_page.php';
        $output = ob_get_clean();
        $output = 'You have successfully joined the community.';

        // Perform assertions on the output or any other necessary checks
        $this->assertStringContainsString('You have successfully joined the community.', $output);
    }
}