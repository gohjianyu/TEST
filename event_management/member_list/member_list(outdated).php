<?PHP
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header ("Location: ../login/login2.php");
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
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}

        border: none;
        color: white;
        padding: 15px 32px;
        text-align: center;
        text-decoration: none;
        display: inline-block;
        font-size: 16px;
        margin: 4px 2px;
        cursor: pointer;
        .button1 {background-color: #008CBA;} 
        .button2 {background-color: #4CAF50;} 
</style>
</head>
<body>

<!-- Navbar -->
<div class="w3-top">
  <div class=" w3-black w3-card w3-left-align w3-large">

    <!-- <a class="w3-bar-item w3-button w3-hide-medium w3-hide-large w3-right w3-padding-large w3-hover-white w3-large w3-red" href="javascript:void(0);" onclick="myFunction()" title="Toggle Navigation Menu"><i class="fa fa-bars"></i></a>
    <a href="homepage2.php" class="w3-bar-item w3-button w3-padding-large w3-white">Home</a> -->
    <div class="navbar">
    <a href="../homepage/homepage2.php">Home</a>
  <a href="../community/create_community.php">Create Community</a>
  <div class="dropdown">
  <button class="dropbtn">My Communities 
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-content">
    <?php

    require '../configure.php';
    // Connect to the database
    $connection = mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
    $db = mysqli_select_db($connection, 'event_management');

    // Query to retrieve communities joined by the current student
    $query = "SELECT community.c_name, community.c_ID
              FROM community
              INNER JOIN joins ON community.c_ID = joins.c_ID
              WHERE joins.s_ID = '{$_SESSION['s_ID']}'";
    
    $query_run = mysqli_query($connection, $query);
    // Iterate over the retrieved communities and display them as dropdown options
    while ($row = mysqli_fetch_array($query_run)) {
      $c_ID = $row['c_ID'];
      echo  '<a href="../community/community_page.php?c_ID=' . $c_ID . '">' . $row['c_name'] . '</a>';
    }
    ?>
  </div>
</div>
  <a href="#">My Account</a>
  <a href="../login/logout.php">Logout</a>
</div>
    <!-- <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">My Account</a>
    <a href="#" class="w3-bar-item w3-button w3-hide-small w3-padding-large w3-hover-white">My Order</a> -->
</div>
</div>

<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
<!-- <h2>Welcome <?php echo $_SESSION['name']?></h2> -->
<!-- <h1 class="w3-margin w3-jumbo">CY T-SHIRT</h1>
  <p class="w3-xlarge">Buy your OWN Tees!</p> -->
<img src="../homepage/distedlogo.png" alt="Disted Logo" style= "height:20%; width:50%;"> 
</header>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <div class="w3-twothird">
      <h1></h1>

      <h1>Member List</h1> 

      <center>
    <form action="" method="POST">
        <table width="100%" border="1" cellpadding="5" cellspacing="5">
            <thead>
                <tr>
                    <th style="width:10%">Student Name</th>
                    <th style="width:30%">Student Email</th>
                    <th style="width:50%">Student Contact Number</th>
                    <th style="width:10%">Role</th>
                </tr>
            </thead>

            
            <?php
            //connect to database 
            $connection= mysqli_connect(DB_SERVER, DB_USER, DB_PASS);
            $db = mysqli_select_db($connection,'event_management');

            $c_IDURL = $_GET['c_ID'];

            //select all the records in the table `community`
            $query = "SELECT student.s_name, student.s_email, student.s_contact_num, joins.role, student.s_ID
            FROM student
            JOIN joins ON student.s_ID = joins.s_ID
            WHERE joins.c_ID = $c_IDURL; ";

            $query_run = mysqli_query($connection,$query);
            while($row = mysqli_fetch_array($query_run))
            {
                 ?>
                 <tr>
                    <td> <?php echo $row['s_name']?></td>
                    <td> <?php echo $row['s_email']?></td>
                    <td> <?php echo $row['s_contact_num']?></td>
                    <td> <?php echo $row['role']?></td>
                    <!-- <td> <?php echo '<form method="get" action="member_list.php">';
                    echo '<input type="hidden" name="studentID" value="' . $row['s_ID'] . '">';
                    echo '<select name="role">';
                    echo '<option value="member">Member</option>';
                    echo '<option value="committee">Committee</option>';
                    echo '<option value="admin">Admin</option>';
                    echo '</select>';
                    echo '<input type="submit" value="Update">';
                    echo '</form>';?> </td> -->
                    <?php
            }
            // if ($query_run) {
            //   // Check if there are any rows returned
            //   if (mysqli_num_rows($query_run) > 0) {
            //       // Display the table headers
            //       echo "<table>";
            //       echo "<tr><th>Student ID</th><th>Name</th><th>Email</th><th>Contact Number</th><th>Role</th><th>Action</th></tr>";
                  
            //       // Loop through each row and display the student information and role
            //       while ($row = mysqli_fetch_assoc($query_run)) {
            //           echo "<tr>";
            //           echo "<td>".$row['s_ID']."</td>";
            //           echo "<td>".$row['s_name']."</td>";
            //           echo "<td>".$row['s_email']."</td>";
            //           echo "<td>".$row['s_contact_num']."</td>";
                      
            //           // Check if the role is editable
            //           $editable = false; // Set this based on your logic for determining if the role can be edited
            //           echo "<td>";
            //           if ($editable) {
            //               // Display a dropdown list with default values from the "joins" table
            //               echo "<form method='POST' action=''>";
            //               echo "<input type='hidden' name='student_id' value='".$row['s_ID']."' />";
            //               echo "<select name='updated_role'>";
            //               echo "<option value='admin' ".($row['role'] == 'admin' ? 'selected' : '').">Admin</option>";
            //               echo "<option value='committee' ".($row['role'] == 'committee' ? 'selected' : '').">Committee</option>";
            //               echo "<option value='member' ".($row['role'] == 'member' ? 'selected' : '').">Member</option>";
            //               echo "</select>";
            //               echo "<input type='submit' name='update_role' value='Update' />";
            //               echo "</form>";
            //           } else {
            //               // Display the role value without editable dropdown
            //               echo $row['role'];
            //           }
            //           echo "</td>";
            //         }
            //       }
            //     }
            ?>

            
 
</center>

    </div>  
  </div>
</div>

<?php
// if (isset($_POST['update_role'])) {
//   // Get the student ID and updated role value from the form
//   $studentID = $_POST['student_id'];
//   $updatedRole = $_POST['updated_role'];
  
//   // Prepare the SQL query to update the role in the "joins" table
//   $updateQuery = "UPDATE joins SET role = '$updatedRole' WHERE s_ID = $studentID";
  
//   // Execute the update query
//   $updateResult = mysqli_query($connection, $updateQuery);
  
//   // Check if the update query executed successfully
//   if ($updateResult) {
//       echo "Role updated successfully.";
//   } else {
//       echo "Error updating role: " . mysqli_error($connection);
//   }
// }
?>
<!-- <?php
require '../footer/footer2.html';
?> -->

<!-- <script >


</script> -->
</body>
</html>


	</body>
	</html>
