<?PHP
$name = "";
$email ="";
$pword = "";
$c_no ="";
$errorMessage = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
	require '../configure.php';

	$name = $_POST['s_name'];
	$email = $_POST['s_email'];
	$pword = $_POST['s_password'];
	$c_no  = $_POST['c_no'];

	$database = "event_management";

	$db_found = new mysqli(DB_SERVER, DB_USER, DB_PASS, $database );

	if ($db_found) {	
		//query statement to see if the email of users/students are the same	
		$SQL = $db_found->prepare('SELECT * FROM student WHERE s_email = ?');
		$SQL->bind_param('s', $email);
		$SQL->execute();
		$result = $SQL->get_result();

	
		if ($result->num_rows > 0) {
			$errorMessage = "Email already taken";
		}

		else if (strlen($name)== 0){
			$errorMessage = "Please enter name";
		}

		else if (strlen($email)== 0){
			$errorMessage = "Please enter email";
		}

		else if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "Invalid email format";
		}
		
		else if (strlen($pword)== 0){
			$errorMessage = "Please enter password";
		}

		else if (strlen($c_no)== 0){
			$errorMessage = "Please enter phone number";
		}

		else if (strlen($c_no)!= 10  && strlen($c_no)!=11){
			$errorMessage = "Invalid phone number";
		}

		

		else {
			//encrypt the password and insert the data input into database
			$phash = password_hash($pword, PASSWORD_DEFAULT);
			$SQL = $db_found->prepare("INSERT INTO student (s_name, s_email, s_password,s_contact_num) VALUES (?, ?, ?, ?)");
			$SQL->bind_param('ssss', $name, $email ,$phash , $c_no );
			$SQL->execute();

			header ("Location: login2.php");
		}
	}
	else {
		$errorMessage = "Database Not Found";
	}
}
?>

	<html>
	<head>
	<title>Basic Signup Script</title>
	<link rel="stylesheet" href="login.css">
	
	</head>
	<body>

<div class="login-page">
<div class="form">


<FORM NAME ="form1" METHOD ="POST" ACTION ="signup2.php">

Name: <INPUT TYPE = 'TEXT' Name ='s_name'  value="<?PHP print $name;?>" >
Email: <INPUT TYPE = 'TEXT' Name ='s_email'  value="<?PHP print $email;?>"  maxlength="20" >
Password: <INPUT TYPE = 'password' Name ='s_password' value="<?PHP print $pword;?>" maxlength="16" >
Contact Number: <INPUT TYPE = 'TEXT' Name ='c_no'  value="<?PHP print $c_no;?>" >

<P>
<!--<INPUT TYPE = "Submit" Name = "Submit1"  VALUE = "Register">-->
<button>Register</button>
<p class="message">Already registered? <a href="login2.php">Login </a></p><br>

<?PHP print "<strong>".$errorMessage."</strong>";?>

</FORM>
</div>
</div>
<P>
	
	</body>
	</html>
