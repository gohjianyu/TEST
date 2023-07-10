<?PHP
session_start();
if (!(isset($_SESSION['login']) && $_SESSION['login'] != '')) {
  header ("Location: ../login/login2.php");
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
<title>Create Community</title>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Lato">
<link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Montserrat">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
<link rel="stylesheet" href="../homepage/homepage2.css">
<link rel="stylesheet" href="create_community.css">
<link rel="stylesheet" href="../feedback/form.css">
<link rel="stylesheet" href="../bg/bg.css">
<script src='https://kit.fontawesome.com/a076d05399.js' crossorigin='anonymous'></script>

<style>

input[type=file]{
    width: 30%;
    height: 5%;
    border: 1px;
    border-radius: 5px;
    padding: 8px 15px 8px 15px;
    margin: 10px 0px 15px 0px;
    box-shadow: 1px 1px 2px 1px grey;
    font-weight: bold;
}
</style>
</head>
<body>

<?php
require '../navbar.php';
?>
<!-- Header -->
<header class="w3-container w3-black w3-center" style="padding:128px 16px">
<img src="../homepage/distedlogo.png" alt="Disted Logo" style= "height:20%; width:50%;"> 
</header>

<!-- First Grid -->
<div class="w3-row-padding w3-padding-64 w3-container">
  <div class="w3-content">
    <center>    
        <h1>Create Community</h1>
        <form action="create_community.php" method="POST" enctype="multipart/form-data">
            
            <label>Community name: </label><br>
            <input type="text" name="c_name" placeholder="Enter Community Name"/><br> 
            <label>Description: </label><br>
            <input type="text" name="c_description" placeholder="Enter Community Description"/><br> 
            <label>Community icon: (Max 64KB)</label><br>
            <input type="file" name="c_image" id="c_image" /><br> <br> <br>
            <input type="submit" name="upload" value="Create Community"/><br>
            <!-- <button> Upload data</button> -->
        </form>
    </center>
  </div>
</div>

<?php
//connect to database 
$connection = mysqli_connect('localhost','root','');
$db = mysqli_select_db($connection,'event_management');

//if the form is uploaded
if(isset($_POST['upload']))
{
    $name = $_POST['c_name'];
    $description = $_POST['c_description'];
    
    //if the connection is established 
    if($db)
    {
        //check if the community of the same name is in the database
        $query = "SELECT * FROM community WHERE c_name = ?";
        $stmt = mysqli_prepare($connection, $query);
        mysqli_stmt_bind_param($stmt, "s", $name);
        mysqli_stmt_execute($stmt);
        $result = mysqli_stmt_get_result($stmt);

        //prints out a message if the name field is empty
        if (strlen($name)==0)
        {
            echo '<script type="text/javascript"> alert("Please enter Community Name")</script>';

        }

        //prints out a message if the description field is empty
        else if (strlen($description)==0)
        {
            echo '<script type="text/javascript"> alert("Please enter Community Description")</script>';

        }

        //prints out a message if the community name is taken
        else if (mysqli_num_rows($result) > 0)
        {
            echo '<script type="text/javascript"> alert("Community Name already taken")</script>';
        }
        
        else
        {
          //check if the $_FILES is not empty 
          if (isset($_FILES['c_image']) && !empty($_FILES['c_image']['tmp_name'])) 
          {
            $file = addslashes(file_get_contents($_FILES["c_image"]["tmp_name"]));
            // upload the file
            $query = "INSERT INTO `community` (`c_name`,`c_image`,`c_description`) VALUES ('$name' , '$file' , '$description')";
            $query_run = mysqli_query($connection,$query);
            $c_id = mysqli_insert_id($connection);

            if($query_run)
            {
              echo '<script type="text/javascript"> alert("Community created successfully")</script>';
              //insert current user id and community id to admin table 
              $joinquery = "INSERT INTO `joins` (`s_ID` , `c_ID`,`role`) VALUES ('{$_SESSION['s_ID']}' , '$c_id','admin')";
              $joinquery_run = mysqli_query($connection, $joinquery);
            }
          } 
          
          else
          {
            // handle the case where no file was uploaded
            echo '<script type="text/javascript"> alert("Image Profile Not Uploaded")</script>';
          }

        }

    }

}
?>

</body>
</html>


	</body>
	</html>
