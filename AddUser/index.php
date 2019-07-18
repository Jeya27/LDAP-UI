<?php 
	session_start();
?>
<!doctype html>
<html>
<head>
	<title>Add New User</title>
	<link href = "style.css" rel="stylesheet" type="text/css">	

	<script>
		function checkPasswordMatch() 
		{
			var password = document.getElementById("pwd").value;
			var confirmPassword = document.getElementById("cnfpwd").value;

			if (password != confirmPassword)
			{
				document.getElementById("match_check").style.color = "red";
				document.getElementById("match_check").innerHTML = "Passwords do not match!";
				document.getElementById("adduser").disabled = true;
			}
			else
			{
				document.getElementById("match_check").style.color = "green";
				document.getElementById("match_check").innerHTML = "Passwords match";
				document.getElementById("adduser").disabled = false;
			}
		}		
	</script>
</head>
	
<body>
<div class="container">
	<h1>Add New User</h1>
	
	<form action="view.php" method="POST" autocompletion="on">
		First Name : <br>
		<input type = "text" name="firstname" id="firstname" placeholder = "Enter Your First Name  For eg:John" required><br>
		
		Last Name : <br>
		<input type = "text" name="lastname" id="lastname" placeholder = "Enter Your Last Name For eg:Doe" required><br>
		
		Common Name : <br>
		<input type = "text" name="cn" id="cn" placeholder = "Enter Your Common Name" required><br>
		
		Employee ID : <br>
		<input type = "text" name="eid" id="eid" placeholder = "Enter Your Employee ID" required><br>
		
		Email ID : <br>
		<input type = "email" name="mail" id="mail" placeholder = "Enter You Email ID" required><br>
		
		User ID : <br>
		<input type = "text" name="uid" id="uid" placeholder = "Enter a UserName" required><br>
		
		Dept : <br>
		<?php
			
			$ldap_dn = "cn=admin,dc=myldap,dc=com";
			$ldap_pwd = "2success";
	
			$ldap_con = ldap_connect("myldap.com");
	
			ldap_set_option($ldap_con, LDAP_OPT_PROTOCOL_VERSION, 3);
	
			if(ldap_bind($ldap_con, $ldap_dn, $ldap_pwd))
			{		
				$filter = "objectclass=posixGroup";
				$search = ldap_search($ldap_con,"dc=myldap,dc=com", $filter);
				$result = ldap_get_entries($ldap_con, $search);
				
				echo "<br><select name='dept' id='dept'>";
				for($i=0; $i<$result["count"]; $i++)
				{
					$val = $result[$i]["cn"][0];
					echo "<option name='$val' value='$val'>".$val."</option>";
				}
			}
			echo "</select><br>";
		?>
		<br>
		<Password : <br>
		<input type = "password" name="pwd" id="pwd" placeholder = "Enter Password" style="width:65%;" required>
		Encode : <select name='encode' id='encode'>
					<option name='clear' value='clear'>Clear</option>
					<option name='md5' value = 'md5' selected>MD5</option>
					<option name='sha' value ='sha'>SHA</option>
					<option name='ssha' value = 'ssha'>SSHA</option>
					<option name='crypt' value = 'crypt'>Crypt</option>
				</select>
		<br>
		
		Confirm Password : <br>
		<input type = "password" name="cnfpwd" id="cnfpwd" 
		 		onkeyup= "checkPasswordMatch()" placeholder="Retype New Password" style="width:65%;" required>
		 		<br>
		 <span id="match_check"></span>
		<br>
		
		<button type="submit" id="adduser">
		Create New User</button>
	</form>
</div>
</body>
</html>
