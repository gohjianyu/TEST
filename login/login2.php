<?PHP
$email = "";
$pword = "";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST'){
	require '../configure.php';

	$email = $_POST['s_email'];
	$pword = $_POST['s_password'];

	$database = "event_management";


	$db_found = new mysqli(DB_SERVER, DB_USER, DB_PASS, $database );

	if ($db_found) {
		
	//query statement to see if the email of users/students are the same	
	$SQL = $db_found->prepare('SELECT * FROM student WHERE s_email = ?');
	$SQL->bind_param('s', $email);
	$SQL->execute();
	$result = $SQL->get_result();

		if ($result->num_rows == 1) {

			$db_field = $result->fetch_assoc();

			//verify password in database and password entered
			if (password_verify($pword, $db_field['s_password'])) {
				session_start();
				$_SESSION['login'] = "1";
				$_SESSION['name'] = $db_field['s_name'];
				$_SESSION['email']= $db_field['s_email'];
				$_SESSION['s_ID']= $db_field['s_ID'];
				header ("Location: ../homepage/homepage2.php");
			}
			else {
				$errorMessage = "Login FAILED";
				session_start();
				$_SESSION['login'] = '';
			}
		}
		else {
			$errorMessage = "username FAILED";
		}
	}
}
?>


<html>
<head>
<title>Login page</title>
<link rel="stylesheet" href="login.css">
</head>
<body>

<div class="login-page">
<div class="form">


<FORM NAME ="form1" METHOD ="POST" ACTION ="login2.php">


Email: <INPUT TYPE = 'TEXT' Name ='s_email'  value="<?PHP print $email;?>" maxlength="20">
Password: <INPUT TYPE = 'PASSWORD' Name ='s_password'  value="<?PHP print $pword;?>" maxlength="16">

<P align = center>
<button>Login</button>
<p class="message">Not registered? <a href="signup2.php">Create an account</a></p><br>
</P>
<?PHP print "<strong>".$errorMessage."</strong>";?>
</FORM>

</div>
</div>
<P>

</body>
</html>