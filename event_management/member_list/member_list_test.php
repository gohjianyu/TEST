<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header("Location: ../login/login2.php");
  exit;
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Member List</title>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="../homepage/homepage2.css">
    <link rel="stylesheet" href="member_list.css">
    <link rel="stylesheet" href="../bg/bg.css">

    <script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

    
    <script>
        function filterTable() {
            // Get the input value
            var input = document.getElementById("filterInput").value.toLowerCase();

            // Get the table rows
            var rows = document.querySelectorAll("#memberTable tr");

            // Loop through the rows and hide those that don't match the filter
            for (var i = 0; i < rows.length; i++) {
                var nameColumn = rows[i].querySelector("td:nth-child(2)");
                var emailColumn = rows[i].querySelector("td:nth-child(3)");

                if (nameColumn && emailColumn) {
                    // Get the text content of the Name and Email columns
                    var name = nameColumn.textContent.toLowerCase();
                    var email = emailColumn.textContent.toLowerCase();

                    // Check if either the Name or Email matches the filter input
                    if (name.includes(input) || email.includes(input)) {
                        rows[i].style.display = ""; // Show the row
                    } else {
                        rows[i].style.display = "none"; // Hide the row
                    }
                }
            }
        }
    </script>
</head>
<body>

<?php
require '../navbar.php';
?>

<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
    <img src="../homepage/distedlogo.png" alt="Disted Logo" style="height:20%; width:50%;">
</header>

<?php                        
$c_IDURL = $_GET['c_ID'];

if (isset($_POST['update_role'])) {
    // Get the student ID and updated role value from the form
    $studentID = $_POST['student_id'];
    $updatedRole = $_POST['updated_role'];

    // Prepare the SQL query to update the role in the "joins" table
    $updateQuery = "UPDATE joins SET role = '$updatedRole' WHERE s_ID = $studentID AND c_ID = $c_IDURL";

    // Execute the update query
    $updateResult = mysqli_query($connection, $updateQuery);

    // Check if the update query executed successfully
    if ($updateResult) {
        echo "<script>alert('Role updated successfully.');</script>";


    } else {
        echo "<script>alert('Error updating role.');</script>" . mysqli_error($connection);
    }
}
?>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
    <div class="w3-content">
        <div class="w3-twothird">

        
            <div>
            <label for="filterInput">Filter:</label>
            <input type="text" id="filterInput" onkeyup="filterTable()">
            </div>
            <h1>Member List</h1>

            <center>
                <form action="" method="POST">
                    <table id = "memberTable" width="100%" border="1" cellpadding="5" cellspacing="5">
                     
                     <?php
                        // Connect to the database
                        $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
                        $db = mysqli_select_db($connection, 'event_management');
                        
                        // Select all the records in the table `student` and `joins`
                        $query = "SELECT student.s_name, student.s_email, student.s_contact_num, joins.role, student.s_ID
                                  FROM student
                                  JOIN joins ON student.s_ID = joins.s_ID
                                  WHERE joins.c_ID = $c_IDURL 
                                  ORDER BY `role`";
                                  
                        $query_run = mysqli_query($connection, $query);
                        if ($query_run) { 
                            // Check if there are any rows returned
                            if (mysqli_num_rows($query_run) > 0) {
                                // Display the table headers
                                echo "<tr><th>Student ID</th><th>Name</th><th>Email</th><th>Contact Number</th><th>Role</th><th>Action</th></tr>";

                                // Loop through each row and display the student information and role
                                while ($row = mysqli_fetch_assoc($query_run)) {
                                    echo "<tr>";
                                    echo "<td>" . $row['s_ID'] . "</td>";
                                    echo "<td>" . $row['s_name'] . "</td>";
                                    echo "<td>" . $row['s_email'] . "</td>";
                                    echo "<td>" . $row['s_contact_num'] . "</td>";

                                    // Check if the role is editable
                                    echo "<td>";
                                    
                                        // Display a dropdown list with default values from the "joins" table
                                        echo "<form method='POST' action=''>";
                                        echo "<input type='hidden' name='student_id' value='" . $row['s_ID'] . "' />";
                                        echo "<select name='updated_role'>";
                                        echo "<option value='admin' " . ($row['role'] == 'admin' ? 'selected' : '') . ">Admin</option>";
                                        echo "<option value='committee' " . ($row['role'] == 'committee' ? 'selected' : '') . ">Committee</option>";
                                        echo "<option value='member' " . ($row['role'] == 'member' ? 'selected' : '') . ">Member</option>";
                                        echo "</select> </td>" ;
                                        echo "<td><input type='submit' name='update_role' value='Update' /> </td>";
                                        echo "</form>";
                                    
                                    echo "</td>";
                                    echo "</tr>";

                                }
                                echo "</table>"; // Close the table
                            }
                        }
                        ?>
                </form>

            
        </div>
    </div>
</div>

</body>
</html>
