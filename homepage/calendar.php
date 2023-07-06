<?php
// Database connection parameters
$host = 'localhost';
$username = 'root';
$password = '';
$database = 'event_management';

// Create a database connection
$connection = mysqli_connect($host, $username, $password, $database);

// Check if the connection was successful
if (!$connection) {
    die('Database connection failed: ' . mysqli_connect_error());
}

// Get current month and year
$currentMonth = date('m');
$currentYear = date('Y');

// Fetch events from the database
$query = "SELECT * FROM event_";
$result = mysqli_query($connection, $query);

// Create an empty array to store events
$events = array();

// Check if the query was successful
if ($result) {
    // Fetch events and store them in the array
    while ($row = mysqli_fetch_assoc($result)) {
        $eventDateStart = date('Y-m-d', strtotime($row['e_date_start']));
        $eventDateEnd = date('Y-m-d', strtotime($row['e_date_end']));

        // Loop through the range of dates and add the event to each date
        $currentDate = $eventDateStart;
        while ($currentDate <= $eventDateEnd) {
            if (!isset($events[$currentDate])) {
                $events[$currentDate] = array();
            }
            $events[$currentDate][] = $row;
            $currentDate = date('Y-m-d', strtotime($currentDate . ' +1 day'));
        }
    }
}

// Close the database connection
mysqli_close($connection);
?>

<!DOCTYPE html>
<html>
<head>
    <style>
        table {
            width: 100%;
            border-collapse: collapse;
        }

        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        td.current-day {
            background-color: #aaffaa;
        }

        td.event-day {
            background-color: #ffffaa;
        }
    </style>
</head>
<body>
    <h2><?php echo date('F Y'); ?></h2>

    <table>
        <tr>
            <th>Mon</th>
            <th>Tue</th>
            <th>Wed</th>
            <th>Thu</th>
            <th>Fri</th>
            <th>Sat</th>
            <th>Sun</th>
        </tr>

        <?php
        // Get the total number of days in the current month
        $daysInMonth = cal_days_in_month(CAL_GREGORIAN, $currentMonth, $currentYear);

        // Calculate the weekday (1 for Monday, 7 for Sunday) of the first day of the month
        // Adjust the value by adding 6 and taking the modulo 7 to make Monday (1) the first day of the week
        $firstDay = (date('N', strtotime($currentYear . '-' . $currentMonth . '-01')) + 6) % 7;

        // Variables for tracking the day count and the current date
        $dayCount = 1;
        $currentDate = 1;

        // Start the first row
        echo '<tr>';

        // Add blank cells for the days before the first day of the month
        for ($i = 0; $i < $firstDay; $i++) {
            echo '<td></td>';
            $dayCount++;
        }

        // Display the days of the month
        while ($currentDate <= $daysInMonth) {
            if ($dayCount > 7) {
                // Start a new row if we reach the end of the week
                echo '</tr><tr>';
                $dayCount = 1;
            }

            echo '<td';

            $currentDateFormatted = sprintf('%02d', $currentDate);
            $currentDateString = $currentYear . '-' . $currentMonth . '-' . $currentDateFormatted;

            if (isset($events[$currentDateString])) {
                // Add the "event-day" class to highlight the cell
                echo ' class="event-day">';
                // Display the day of the month
                echo $currentDate . '<br>';
                // Display the event names for the current date
                foreach ($events[$currentDateString] as $event) {
                    echo "<a href= '../event_/event_detail.php?e_ID=".$event['e_ID']."&c_ID=".$event['c_ID']."'>" .$event['e_name'] . '<br>';
                }
            } elseif ($currentDateString == date('Y-m-d')) {
                // Add the "current-day" class to highlight the cell
                echo ' class="current-day">';
                // Display the day of the month
                echo $currentDate;
            } else {
                // Display the day of the month
                echo '>';
                echo $currentDate;
            }

            echo '</td>';
            $currentDate++;
            $dayCount++;
        }

        // Complete the last row with blank cells if necessary
        while ($dayCount <= 7) {
            echo '<td></td>';
            $dayCount++;
        }

        echo '</tr>';
        ?>
    </table>
</body>
</html>
