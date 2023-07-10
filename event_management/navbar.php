<!-- Navbar -->
<div class="w3-top">
  <div class="w3-black w3-card w3-left-align w3-large">

    <div class="navbar">
      <a href="../homepage/homepage2.php">Home</a>
      <a href="../community/create_community.php">Create Community</a>
      <div class="dropdown">
  <button class="dropbtn">My Communities 
    <i class="fa fa-caret-down"></i>
  </button>
  <div class="dropdown-content">
    <?php

    require "C:/xampp/htdocs/event_management/configure.php";
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

  <a href="../notification/mailbox.php">Mail</a>
  <a href="../login/logout.php">Logout</a>
</div>
</div>  
</div>
