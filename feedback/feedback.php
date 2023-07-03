<?php
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header("Location: ../login/login2.php");
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Feedback Page</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../homepage/homepage2.css">
<link rel="stylesheet" href="feedback.css">
<link rel="stylesheet" href="form.css">
<link rel="stylesheet" href="../bg/bg.css">

<!-- <link rel="stylesheet" href="../community/create_community.css"> -->
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>
body,h1,h2,h3,h4,h5,h6 {font-family: "Lato", sans-serif}
.w3-bar,h1,button {font-family: "Montserrat", sans-serif}
.fa-anchor,.fa-coffee {font-size:200px}

input[type=text]{
    width: 50%;
    height: 5%;
    border: 1px;
    border-radius: 5px;
    padding: 8px 15px 8px 15px;
    margin: 10px 0px 15px 0px;
    box-shadow: 1px 1px 2px 1px grey;
    font-weight: bold;
}

*{
    margin: 0;
    padding: 0;
}
.rate {
    display: inline-block;
    height: 46px;
    padding: 0 10px;
}
.rate:not(:checked) > input {
    position:absolute;
    visibility: hidden;
}

.rate:not(:checked) > label {
    float:right;
    width:1em;
    overflow:hidden;
    white-space:nowrap;
    cursor:pointer;
    font-size:40px;
    color:#ccc;
}
.rate:not(:checked) > label:before {
    content: '★ ';
}
.rate > input:checked ~ label {
    color: #ffc700;
}
.rate:not(:checked) > label:hover,
.rate:not(:checked) > label:hover ~ label {
    color: #deb217;
}
.rate > input:checked + label:hover,
.rate > input:checked + label:hover ~ label,
.rate > input:checked ~ label:hover,
.rate > input:checked ~ label:hover ~ label,
.rate > label:hover ~ input:checked ~ label {
    color: #c59b08;
}

</style>
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
$e_IDURL = $_GET['e_ID'];

if (isset($_POST['submit'])) 
{
    $feedback_description = $_POST['feedback_description'];
    $feedback_rating = $_POST['rating'];

    // Check if the feedback description is empty
    if (empty($feedback_description)) 
    {
        echo '<script>alert("Feedback description is empty!");</script>';
    }
    else
    {
        // Insert the feedback posted to the database
        $sql = "INSERT INTO `feedback` (`f_content`, `f_rating`, `e_ID`, `c_ID`) VALUES ('$feedback_description', '$feedback_rating', '$e_IDURL', '$c_IDURL')";
        $result = mysqli_query($connection, $sql);
        
        if ($result)
        {
          header("Location: ../community/community_page.php?c_ID=" . $c_IDURL . "&feedback=success");
        }
        else
        {
          echo '<script type="text/javascript"> alert("Failed to send your feedback")</script>';
        }

    }
}
?>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <center>    
        <h1>Feedback</h1>
        <?php
        $query_event = "SELECT * FROM event_ WHERE e_ID=$e_IDURL;";
        $query_event_run = mysqli_query($connection, $query_event);
        $row = mysqli_fetch_assoc($query_event_run);

        ?>
        <form action="" method="POST">
            <label>Event name: </label><br>
            <input type="text" name="e_name" placeholder=" <?php echo $row['e_name']?>" readonly /><br> 
            <label>Feedback: </label><br>
            <textarea name="feedback_description" placeholder="Your feedback here" style="max-width: 500px; max-height: 300px; width: 500px; height: 300px; min-width: 500px; min-height: 150px;"></textarea><br> 
            <label>Ratings: </label><br>
            <div class="rate">  
              <input type="radio" name="rating" id ="star5" value="5">
              <label for="star5" title="Rating">5 stars</label>

              <input type="radio" name="rating" id ="star4" value="4">
              <label for="star4" title="Rating">4 stars</label>

              <input type="radio" name="rating" id ="star3" value="3">
              <label for="star3" title="Rating">3 stars</label>

              <input type="radio" name="rating" id ="star2" value="2">
              <label for="star2" title="Rating">2 stars</label>

              <input type="radio" name="rating" id ="star1" value="1" checked ="checked">
              <label for="star1" title="Rating">1 stars</label>
              </div>
              <br><br>
            <input type="submit" name="submit" value="Submit Feedback"/><br>
            <br>
            <!-- <button> Upload data</button> -->
      
        </form>
    </center>
  </div>
</div>

