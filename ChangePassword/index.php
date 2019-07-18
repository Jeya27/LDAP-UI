<?php
	session_start();	
?>
<!doctype html>
<html>
<head>
	<title>Change Password</title>
	<link href="style.css" rel = "stylesheet" type = "text/css">
	<script>
		function checkPasswordMatch() 
		{
			var password = document.getElementById("newpwd").value;
			var confirmPassword = document.getElementById("cnfpwd").value;

			if (password != confirmPassword)
			{
				document.getElementById("match_check").style.color = "red";
				document.getElementById("match_check").innerHTML = "Passwords do not match!";
				document.getElementById("submit").disabled = true;
			}
			else
			{
				document.getElementById("match_check").style.color = "green";
				document.getElementById("match_check").innerHTML = "Passwords match";
				document.getElementById("submit").disabled = false;
			}
		}	
		
		function submitform()
		{
			document.forms['jsform'].action="action_change_pwd.php";
			document.forms['jsform'].submit();
			
			document.forms['chngform'].action="action_change_pwd.php";
			document.forms['chngform'].submit();
		}
	</script>	
</head>
<body>

	<button onclick="location.href='logpage.php';" style="width:10%;position:absolute;top:2%;right:2%;">Log</button>
	
	<button onclick="location.href='active.php';" style="width:10%;position:absolute;top:15%;right:2%;">Active Users</button>
<br><br>

	<h1>Change Password</h1>
	<br>
	<p class="container" id="first">Please Verify User Before entering New Password!!!</p>
	<p class="container" id="second" hidden="true">Now Change Your Password!!!</p>
	<div class = "container formborder">
	<form action = "login.php" id="loginform" method = "POST" autocompletion="on">
		Username : <br>
		<input type = "text" id = "user" name="user" placeholder="Enter Your Employee ID" autocomplete="on" required>
		<br>
		
		Password : <br>
		<input type = "password" name="pwd" id="pwd" placeholder="Enter Your Password" autocomplete="on">
		
		<input type="checkbox" name="verify" id="verify" 
		onclick ="document.getElementById('loginform').submit();">Verify User
		<br>		
		<span id="login_check"></span>
	</form>
	
	<form action = "action_change_pwd.php" id = "chngform" method = "POST" autocompletion="off" disabled="true">
	
		New Password : <br>
		<input type = "password" name="newpwd" id="newpwd" 			placeholder="Enter New Password" autocomplete="off" required>
		<br>
		
		Confirm Password : <br>
		 <input type = "password" name="cnfpwd" id="cnfpwd" 
		 		onkeyup= "checkPasswordMatch()" 
		 			placeholder="Retype New Password" autocomplete="off"
		 				required>
		 <span id="match_check"></span>
		<br>
		
		<button type = "submit" id="submit" onclick="submitform()">Change Password</button>
	</form>
	</div>
</body>
</html>
<?php
	function remove_user()
	{	
		echo "Came in!!<br>";
		require_once("fSQL.php");
		
		$user = $_SESSION['user'];

		if($user!= "")
		{
			$fsql = new fSQLEnvironment;
		
			$fsql->define_db("userdb", "Active")
    			or die("DEFINE_DB userdb failed: ".$fsql->error());
			echo "DB is defined<br>";
			$fsql->select_db("userdb")
				or die("SELECT DB userdb failed: ".$fsql->error());
			echo "DB is set<br>";		
			$results = $fsql->query("DELETE FROM activeusers WHERE Userid='$user'")
  				or die("DELETE failed: ".$fsql->error());
  		
  			echo "Deleted Users<br>";
  		}
  			
  			//header("location:index.php");
	}
	
?>
<!-- pattern="((A-Z)(.*\d)(.*[A-Z]){8,}" title = "Start with a Uppercase character.
Must contain atleast a digit and a special character.
Password Minimum length = 8" -->
